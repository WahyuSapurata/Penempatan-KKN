<?php

namespace App\Services;

use App\Models\Angkatan;
use App\Models\Mahasiswa;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Lokasi;
use App\Models\SubKriteria;

class PrometheeService
{
    public function proses()
    {
        // 1. Ambil data
        $angkatan = Angkatan::where('status', 'Aktiv')->first();

        if (!$angkatan) {
            return response()->json(['success' => false, 'message' => 'Data angkatan aktif tidak ditemukan.', 'data' => []], 400);
        }

        $mahasiswa = Mahasiswa::where('uuid_angkatan', $angkatan->uuid)->where('status', 'Terkonfirmasi')->get();
        $kriteria = Kriteria::where(function ($query) use ($angkatan) {
            $query->where('uuid_angkatan', $angkatan->uuid)
                ->orWhereNull('uuid_angkatan');
        })
            ->get();
        $penilaian = Penilaian::all();

        if ($mahasiswa->isEmpty() || $kriteria->isEmpty() || $penilaian->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Data mahasiswa, kriteria, atau penilaian tidak tersedia.', 'data' => []], 400);
        }

        // 2. Validasi bobot kriteria
        $totalBobotKriteria = $kriteria->sum('bobot');
        if ($totalBobotKriteria != 100) {
            return response()->json(['success' => false, 'message' => 'Total bobot kriteria harus 100.', 'data' => []], 400);
        }

        // 3. Ambil semua subkriteria dan kelompokkan berdasarkan uuid_kriteria
        $subkriteriaList = SubKriteria::all()->keyBy('uuid');
        $subkriteriaGrouped = $subkriteriaList->groupBy('uuid_kriteria');

        // 4. Susun data nilai berdasarkan bobot subkriteria
        $dataNilai = [];

        foreach ($penilaian as $p) {
            $sub = $subkriteriaList[$p->uuid_subkriteria] ?? null;
            if ($sub) {
                $dataNilai[$p->uuid_mahasiswa][$p->uuid_kriteria][] = $sub->bobot;
            }
        }

        // Hitung rata-rata bobot per kriteria per mahasiswa
        foreach ($dataNilai as $mhsUuid => $nilaiKriteria) {
            foreach ($nilaiKriteria as $kriteriaUuid => $nilai) {
                $dataNilai[$mhsUuid][$kriteriaUuid] = count($nilai) ? array_sum($nilai) / count($nilai) : 0;
            }
        }

        // 5. Hitung nilai min dan max per kriteria
        $minSub = [];
        $maxSub = [];

        foreach ($kriteria as $k) {
            $nilaiList = [];

            foreach ($dataNilai as $nilaiMhs) {
                if (isset($nilaiMhs[$k->uuid])) {
                    $nilaiList[] = $nilaiMhs[$k->uuid];
                }
            }

            $minSub[$k->uuid] = count($nilaiList) ? min($nilaiList) : 0;
            $maxSub[$k->uuid] = count($nilaiList) ? max($nilaiList) : 1;
        }

        // 6. Normalisasi nilai
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

        // 7. Hitung preferensi antar mahasiswa
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

        // 8. Hitung net flow untuk setiap mahasiswa
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

        // 9. Ranking berdasarkan net flow
        arsort($netFlow);
        $ranking = [];

        // Ambil UUID kriteria jurusan
        $kriteriaJurusan = $kriteria->first(fn($item) => $item->nama_kriteria === 'Jurusan');
        $kriteriaFakultas = $kriteria->first(fn($item) => $item->nama_kriteria === 'Fakultas');

        foreach ($netFlow as $uuid => $nilai) {
            $mhs = $mahasiswa->firstWhere('uuid', $uuid);

            // Default nilai jika tidak ditemukan
            $jurusanNama = '-';
            $fakultasNama = '-';

            if ($mhs) {
                // Cari nilai jurusan
                if ($kriteriaJurusan) {
                    $penilaianJurusan = $penilaian->firstWhere(function ($p) use ($mhs, $kriteriaJurusan) {
                        return $p->uuid_mahasiswa === $mhs->uuid && $p->uuid_kriteria === $kriteriaJurusan->uuid;
                    });

                    if ($penilaianJurusan) {
                        $sub = $subkriteriaList[$penilaianJurusan->uuid_subkriteria] ?? null;
                        $jurusanNama = $sub->nama ?? '-';
                    }
                }

                // Cari nilai fakultas
                if ($kriteriaFakultas) {
                    $penilaianFakultas = $penilaian->firstWhere(function ($p) use ($mhs, $kriteriaFakultas) {
                        return $p->uuid_mahasiswa === $mhs->uuid && $p->uuid_kriteria === $kriteriaFakultas->uuid;
                    });

                    if ($penilaianFakultas) {
                        $sub = $subkriteriaList[$penilaianFakultas->uuid_subkriteria] ?? null;
                        $fakultasNama = $sub->nama ?? '-';
                    }
                }
            }

            $ranking[] = [
                'uuid' => $uuid,
                'nama' => $mhs->nama ?? '-',
                'jurusan' => $jurusanNama,
                'fakultas' => $fakultasNama,
                'nim' => $mhs->nim ?? '-',
                'net_flow' => $nilai,
            ];
        }

        // 10. Alokasi Lokasi
        $lokasiList = Lokasi::where('uuid_angkatan', $angkatan->uuid)->get();
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

        // return response()->json(['success' => true, 'message' => 'Data berhasil diproses.', 'data' => array_values($terisi)], 200);
    }
}
