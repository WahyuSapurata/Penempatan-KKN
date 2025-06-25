<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Lokasi;
use App\Models\SubKriteria;

class PrometheeService
{
    public function proses($params)
    {
        // 1. Ambil data
        $mahasiswa = Mahasiswa::where('uuid_angkatan', $params)->get();
        $kriteria = Kriteria::all();
        $penilaian = Penilaian::all();

        if ($mahasiswa->isEmpty() || $kriteria->isEmpty() || $penilaian->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Data mahasiswa, kriteria, atau penilaian tidak tersedia.', 'data' => []], 400);
        }

        // 2. Validasi bobot kriteria
        $totalBobotKriteria = $kriteria->sum('bobot');
        if ($totalBobotKriteria != 100) {
            return response()->json(['success' => false, 'message' => 'Total bobot kriteria harus 100.', 'data' => []], 400);
        }

        // 3. Ambil semua subkriteria dan kelompokkan berdasarkan kriteria
        $subkriteriaList = SubKriteria::all()->keyBy('uuid');
        $subkriteria = $subkriteriaList->groupBy('uuid_kriteria');

        // 4. Susun data nilai berdasarkan bobot subkriteria
        $dataNilai = [];

        foreach ($penilaian as $p) {
            if (isset($subkriteriaList[$p->uuid_subkriteria])) {
                $sub = $subkriteriaList[$p->uuid_subkriteria];
                $kriteriaUuid = $sub->uuid_kriteria;
                $dataNilai[$p->uuid_mahasiswa][$kriteriaUuid][] = $sub->bobot;
            }
        }

        // Hitung nilai rata-rata per kriteria
        foreach ($dataNilai as $mhsUuid => $kriteriaArray) {
            foreach ($kriteriaArray as $kriteriaUuid => $bobotList) {
                $dataNilai[$mhsUuid][$kriteriaUuid] = count($bobotList) ? array_sum($bobotList) / count($bobotList) : 0;
            }
        }

        // 5. Hitung min dan max tiap subkriteria
        // Hitung min dan max per kriteria
        $minSub = [];
        $maxSub = [];

        foreach ($kriteria as $k) {
            $nilaiList = [];

            foreach ($dataNilai as $mhs) {
                if (isset($mhs[$k->uuid])) {
                    $nilaiList[] = $mhs[$k->uuid];
                }
            }

            $minSub[$k->uuid] = count($nilaiList) ? min($nilaiList) : 0;
            $maxSub[$k->uuid] = count($nilaiList) ? max($nilaiList) : 1;
        }

        // 6. Normalisasi dan total per kriteria
        $normalisasi = [];

        foreach ($dataNilai as $uuid_mhs => $nilaiMhs) {
            foreach ($kriteria as $k) {
                $nilai = $nilaiMhs[$k->uuid] ?? 0;
                $min = $minSub[$k->uuid];
                $max = $maxSub[$k->uuid];

                $normal = ($max == $min) ? 0 : (
                    strtolower($k->jenis) === 'benefit'
                    ? ($nilai - $min) / ($max - $min)
                    : ($max - $nilai) / ($max - $min)
                );

                $normalisasi[$uuid_mhs][$k->uuid] = $normal;
            }
        }

        // 6. Hitung preferensi
        $preferensi = [];
        $uuids = array_keys($normalisasi);
        foreach ($uuids as $uuidA) {
            foreach ($uuids as $uuidB) {
                if ($uuidA == $uuidB) continue;
                $pref = 0;
                foreach ($kriteria as $k) {
                    $diff = $normalisasi[$uuidA][$k->uuid] - $normalisasi[$uuidB][$k->uuid];
                    $pref += max(0, $diff) * ($k->bobot / 100);
                }
                $preferensi[$uuidA][$uuidB] = $pref;
            }
        }

        // 7. Net flow
        $netFlow = [];
        foreach ($uuids as $uuidA) {
            $leaving = $entering = 0;
            foreach ($uuids as $uuidB) {
                if ($uuidA == $uuidB) continue;
                $leaving += $preferensi[$uuidA][$uuidB] ?? 0;
                $entering += $preferensi[$uuidB][$uuidA] ?? 0;
            }
            $n = count($uuids) - 1;
            $netFlow[$uuidA] = $n > 0 ? ($leaving - $entering) / $n : 0;
        }

        // 8. Ranking
        arsort($netFlow);
        $ranking = [];
        foreach ($netFlow as $uuid => $nilai) {
            $mhs = $mahasiswa->firstWhere('uuid', $uuid);
            $ranking[] = [
                'uuid' => $uuid,
                'nama' => $mhs->nama ?? '-',
                'jurusan' => $mhs->jurusan ?? '-',
                'nim' => $mhs->nim ?? '-',
                'net_flow' => $nilai,
            ];
        }

        // 9. Alokasi Lokasi
        $lokasiList = Lokasi::orderBy('jarak')->get();
        if ($lokasiList->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Data lokasi tidak tersedia.', 'data' => []], 400);
        }

        $terisi = [];
        foreach ($lokasiList as $lokasi) {
            $terisi[$lokasi->uuid] = [
                'uuid' => $lokasi->uuid,
                'nama_lokasi' => $lokasi->lokasi,
                'kuota' => $lokasi->kuota,
                'mahasiswa' => [],
                'jurusan_count' => [],
            ];
        }

        foreach ($ranking as $mhs) {
            foreach ($lokasiList as $lokasi) {
                $lok = &$terisi[$lokasi->uuid];
                $jumlahJurusan = $lok['jurusan_count'][$mhs['jurusan']] ?? 0;

                if (count($lok['mahasiswa']) < $lok['kuota'] && $jumlahJurusan < 2) {
                    $mhs['nama_lokasi'] = $lok['nama_lokasi'];
                    $lok['mahasiswa'][] = $mhs;
                    $lok['jurusan_count'][$mhs['jurusan']] = $jumlahJurusan + 1;
                    break;
                }
            }
        }

        return $terisi;
    }
}
