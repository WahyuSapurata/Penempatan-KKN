<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'uuid' => Uuid::uuid4()->toString(),
                'name' => 'Admin',
                'role' => 'admin',
                'password' => Hash::make('<>password'),
            ]
        );

        $kriteria = Kriteria::updateOrCreate(
            ['nama_kriteria' => 'Jenis Kelamin'],
            [
                'uuid' => Uuid::uuid4()->toString(),
                'bobot' => '20',
                'jenis' => 'Benefit',
            ]
        );

        // Pastikan kita mendapatkan UUID-nya, baik dari hasil update maupun create
        $uuidKriteria = $kriteria->uuid;

        // Buat SubKriteria: Laki-laki
        SubKriteria::updateOrCreate(
            ['nama' => 'Laki-laki'],
            [
                'uuid' => Uuid::uuid4()->toString(),
                'uuid_kriteria' => $uuidKriteria,
                'bobot' => '15',
            ]
        );

        // Buat SubKriteria: Perempuan
        SubKriteria::updateOrCreate(
            ['nama' => 'Perempuan'],
            [
                'uuid' => Uuid::uuid4()->toString(),
                'uuid_kriteria' => $uuidKriteria,
                'bobot' => '10',
            ]
        );

        // Seeder Kriteria Fakultas
        $kriteriaFakultas = Kriteria::updateOrCreate(
            ['nama_kriteria' => 'Fakultas'],
            [
                'uuid' => Uuid::uuid4()->toString(),
                'bobot' => '20',
                'jenis' => 'Benefit',
            ]
        );

        // Seeder Kriteria Jurusan
        $kriteriaJurusan = Kriteria::updateOrCreate(
            ['nama_kriteria' => 'Jurusan'],
            [
                'uuid' => Uuid::uuid4()->toString(),
                'bobot' => '20',
                'jenis' => 'Benefit',
            ]
        );

        $uuidFakultas = $kriteriaFakultas->uuid;
        $uuidJurusan = $kriteriaJurusan->uuid;

        // Data Fakultas dan Jurusan
        $dataFakultas = [
            [
                'nama' => "Syariah dan Hukum",
                'jurusan' => [
                    "Hukum Keluarga (Ahwal Syakhshiyyah)",
                    "Hukum Tata Negara (Siyasah)",
                    "Hukum Ekonomi Syariah (Muamalah)",
                    "Ilmu Hukum",
                    "Perbandingan Mazhab dan Hukum"
                ]
            ],
            [
                'nama' => "Ushuluddin dan Filsafat",
                'jurusan' => [
                    "Akidah dan Filsafat Islam",
                    "Ilmu Al-Qur'an dan Tafsir",
                    "Ilmu Hadis",
                    "Studi Agama-Agama"
                ]
            ],
            [
                'nama' => "Tarbiyah dan Keguruan",
                'jurusan' => [
                    "Pendidikan Agama Islam",
                    "Pendidikan Bahasa Arab",
                    "Manajemen Pendidikan Islam",
                    "Pendidikan Guru Madrasah Ibtidaiyah (PGMI)",
                    "Pendidikan Islam Anak Usia Dini (PIAUD)",
                    "Tadris Bahasa Inggris",
                    "Tadris Ilmu Pengetahuan Alam",
                    "Tadris Matematika",
                    "Tadris Biologi",
                    "Tadris Fisika",
                    "Tadris Kimia",
                    "Pendidikan Profesi Guru (PPG)"
                ]
            ],
            [
                'nama' => "Ekonomi dan Bisnis Islam",
                'jurusan' => [
                    "Ekonomi Syariah",
                    "Perbankan Syariah",
                    "Akuntansi Syariah",
                    "Manajemen Zakat dan Wakaf"
                ]
            ],
            [
                'nama' => "Dakwah dan Komunikasi",
                'jurusan' => [
                    "Komunikasi dan Penyiaran Islam",
                    "Bimbingan dan Konseling Islam",
                    "Manajemen Dakwah",
                    "Pengembangan Masyarakat Islam",
                    "Ilmu Komunikasi",
                    "Jurnalistik"
                ]
            ],
            [
                'nama' => "Sains dan Teknologi",
                'jurusan' => [
                    "Teknik Informatika",
                    "Sistem Informasi",
                    "Teknik Arsitektur",
                    "Teknik Lingkungan",
                    "Matematika",
                    "Biologi",
                    "Fisika",
                    "Kimia"
                ]
            ],
            [
                'nama' => "Kedokteran dan Ilmu Kesehatan",
                'jurusan' => [
                    "Pendidikan Dokter",
                    "Kesehatan Masyarakat",
                    "Ilmu Keperawatan",
                    "Farmasi",
                    "Profesi Dokter",
                    "Profesi Ners"
                ]
            ],
            [
                'nama' => "Adab dan Humaniora",
                'jurusan' => [
                    "Bahasa dan Sastra Arab",
                    "Sejarah dan Kebudayaan Islam",
                    "Ilmu Perpustakaan",
                    "Bahasa dan Sastra Inggris"
                ]
            ]
        ];

        foreach ($dataFakultas as $fakultas) {
            // Tambahkan SubKriteria Fakultas
            SubKriteria::updateOrCreate(
                ['nama' => $fakultas['nama']],
                [
                    'uuid' => Uuid::uuid4()->toString(),
                    'uuid_kriteria' => $uuidFakultas,
                    'bobot' => '10', // atau sesuaikan bobotnya
                ]
            );

            // Tambahkan SubKriteria Jurusan
            foreach ($fakultas['jurusan'] as $jurusan) {
                SubKriteria::updateOrCreate(
                    ['nama' => $jurusan],
                    [
                        'uuid' => Uuid::uuid4()->toString(),
                        'uuid_kriteria' => $uuidJurusan,
                        'bobot' => '10', // atau sesuaikan bobotnya
                    ]
                );
            }
        }
    }
}
