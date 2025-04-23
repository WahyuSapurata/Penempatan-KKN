<?php

namespace App\Services;

use App\Models\Mahasiswa;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Lokasi;

class PrometheeService
{
    public function proses($params)
    {
        // 1. Ambil data mahasiswa, kriteria, dan penilaian
        $mahasiswa = Mahasiswa::where('uuid_angkatan', $params)->get();
        $kriteria = Kriteria::all();
        $penilaian = Penilaian::all();

        // 2. Struktur data penilaian per mahasiswa
        $dataNilai = [];
        foreach ($penilaian as $nilai) {
            $dataNilai[$nilai->uuid_mahasiswa][$nilai->uuid_kriteria] = $nilai->nilai;
        }

        // 3. Cari nilai min dan max tiap kriteria
        $minKriteria = [];
        $maxKriteria = [];
        foreach ($kriteria as $k) {
            $nilaiKriteria = [];
            foreach ($dataNilai as $nilaiMhs) {
                if (isset($nilaiMhs[$k->uuid])) {
                    $nilaiKriteria[] = $nilaiMhs[$k->uuid];
                }
            }
            $minKriteria[$k->uuid] = min($nilaiKriteria);
            $maxKriteria[$k->uuid] = max($nilaiKriteria);
        }

        // 4. Normalisasi nilai
        $normalisasi = [];
        foreach ($dataNilai as $uuid_mhs => $nilaiMhs) {
            foreach ($kriteria as $k) {
                $min = $minKriteria[$k->uuid];
                $max = $maxKriteria[$k->uuid];
                $nilai = $nilaiMhs[$k->uuid] ?? 0;

                $jenis = strtolower($k->jenis); // agar 'Benefit'/'Cost' bisa dimasukkan bebas kapital
                $normal = ($max == $min) ? 0 : (
                    $jenis === 'benefit'
                    ? ($nilai - $min) / ($max - $min)
                    : ($max - $nilai) / ($max - $min)
                );

                $normalisasi[$uuid_mhs][$k->uuid] = $normal;
            }
        }

        // 5. Preferensi antar mahasiswa
        $preferensi = [];
        $uuids = array_keys($normalisasi);
        foreach ($uuids as $uuidA) {
            foreach ($uuids as $uuidB) {
                if ($uuidA == $uuidB) continue;

                $pref = 0;
                foreach ($kriteria as $k) {
                    $diff = $normalisasi[$uuidA][$k->uuid] - $normalisasi[$uuidB][$k->uuid];
                    $p = max(0, $diff);
                    $pref += $p * ($k->bobot / 10);
                }

                $preferensi[$uuidA][$uuidB] = $pref;
            }
        }

        // 6. Hitung Leaving Flow, Entering Flow, Net Flow
        $netFlow = [];
        $leavingFlow = [];
        $enteringFlow = [];
        foreach ($uuids as $uuidA) {
            $sumLeaving = 0;
            $sumEntering = 0;
            foreach ($uuids as $uuidB) {
                if ($uuidA == $uuidB) continue;
                $sumLeaving += $preferensi[$uuidA][$uuidB] ?? 0;
                $sumEntering += $preferensi[$uuidB][$uuidA] ?? 0;
            }

            $n = count($uuids) - 1;
            $leavingFlow[$uuidA] = $sumLeaving / $n;
            $enteringFlow[$uuidA] = $sumEntering / $n;
            $netFlow[$uuidA] = $leavingFlow[$uuidA] - $enteringFlow[$uuidA];
        }

        // 7. Ranking berdasarkan Net Flow
        arsort($netFlow);
        $ranking = [];
        foreach ($netFlow as $uuid => $nilai) {
            $mhs = $mahasiswa->firstWhere('uuid', $uuid);
            $ranking[] = [
                'uuid' => $uuid ?? '',
                'nama' => $mhs->nama ?? '',
                'jurusan' => $mhs->jurusan ?? '',
                'nim' => $mhs->nim ?? '',
                'net_flow' => $nilai ?? '',
            ];
        }

        // 8. Alokasi lokasi KKN dengan nama lokasi
        $lokasiList = Lokasi::orderBy('jarak')->get(); // Lokasi harus punya: uuid, nama, kuota
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

                if (
                    count($lok['mahasiswa']) < $lok['kuota'] &&
                    $jumlahJurusan < 2
                ) {
                    $mhs['nama_lokasi'] = $lok['nama_lokasi']; // Tambahkan nama lokasi ke mahasiswa
                    $lok['mahasiswa'][] = $mhs;
                    $lok['jurusan_count'][$mhs['jurusan']] = $jumlahJurusan + 1;
                    break;
                }
            }
        }

        return $terisi;
    }
}
