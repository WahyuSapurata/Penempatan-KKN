<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Models\Angkatan;
use App\Models\Kriteria;
use App\Models\Mahasiswa;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MahasiswaController extends BaseController
{
    public function index()
    {
        $module = 'Mahasiswa';
        $angkatan = Angkatan::where('status', 'Aktiv')->first();
        if (!$angkatan) {
            return redirect()->route('admin.angkatan')->with('failed', 'Angkatan aktiv belum di tentukan');
        }
        return view('admin.mahasiswa.index', compact('module', 'angkatan'));
    }

    public function get()
    {
        $angkatan = Angkatan::where('status', 'Aktiv')->first();
        $mahasiswa = Mahasiswa::where('uuid_angkatan', $angkatan->uuid)->latest()->get();
        return $this->sendResponse($mahasiswa, 'Get data success');
    }

    public function add()
    {
        $module = 'Tambah Data Mahasiswa';
        $kriteria = Kriteria::all();
        $kriteria->map(function ($item) {
            $sub_krtiteria = SubKriteria::where('uuid_kriteria', $item->uuid)->get();
            $item->subkriteria = $sub_krtiteria;

            return $item;
        });
        return view('admin.mahasiswa.add', compact('module', 'kriteria'));
    }

    public function store(StoreMahasiswaRequest $store_mahasiswa_request)
    {
        $newTranskrip = '';
        $newKelakuan = '';
        $newPernyataan = '';
        if ($store_mahasiswa_request->file('transkrip')) {
            $extension = $store_mahasiswa_request->file('transkrip')->extension();
            $newTranskrip = 'transkrip' . '-' . now()->timestamp . '.' . $extension;
            $store_mahasiswa_request->file('transkrip')->storeAs('public/mahasiswa', $newTranskrip);
        }

        if ($store_mahasiswa_request->file('kelakuan_baik')) {
            $extension = $store_mahasiswa_request->file('kelakuan_baik')->extension();
            $newKelakuan = 'kelakuan_baik' . '-' . now()->timestamp . '.' . $extension;
            $store_mahasiswa_request->file('kelakuan_baik')->storeAs('public/mahasiswa', $newKelakuan);
        }

        if ($store_mahasiswa_request->file('pernyataan_kesiapan')) {
            $extension = $store_mahasiswa_request->file('pernyataan_kesiapan')->extension();
            $newPernyataan = 'pernyataan_kesiapan' . '-' . now()->timestamp . '.' . $extension;
            $store_mahasiswa_request->file('pernyataan_kesiapan')->storeAs('public/mahasiswa', $newPernyataan);
        }

        $angkatan = Angkatan::where('status', 'Aktiv')->first();
        $mahasiswa = Mahasiswa::create([
            'uuid_angkatan' => $angkatan->uuid,
            'nim' => $store_mahasiswa_request->nim,
            'nama' => $store_mahasiswa_request->nama,
            'semester' => $store_mahasiswa_request->semester,
            'sks' => $store_mahasiswa_request->sks,
            'status' => 'Belum Diverifikasi',
            'transkrip' => $newTranskrip,
            'kelakuan_baik' => $newKelakuan,
            'pernyataan_kesiapan' => $newPernyataan,
        ]);

        foreach ($store_mahasiswa_request->subkriteria ?? [] as $kriteria_uuid => $uuid_subkriteria) {
            if ($uuid_subkriteria) {
                Penilaian::create([
                    'uuid_mahasiswa' => $mahasiswa->uuid,
                    'uuid_kriteria' => $kriteria_uuid,
                    'uuid_subkriteria' => $uuid_subkriteria,
                ]);
            }
        }

        return $this->sendResponse(null, 'Add data success');
    }

    public function edit($params)
    {
        $module = 'Edit Mahasiswa';

        // Ambil data mahasiswa berdasarkan UUID
        $mahasiswa = Mahasiswa::where('uuid', $params)->firstOrFail();

        // Ambil semua kriteria
        $kriteria = Kriteria::latest()->get();

        // Tambahkan subkriteria secara manual ke setiap item
        $kriteria->map(function ($item) {
            $item->subkriteria = SubKriteria::where('uuid_kriteria', $item->uuid)->get();
            return $item;
        });

        // Ambil data penilaian mahasiswa
        $penilaian = Penilaian::where('uuid_mahasiswa', $params)->get();

        // Siapkan array [uuid_kriteria => uuid_subkriteria]
        $subkriteria_terpilih = [];

        foreach ($penilaian ?? [] as $item) {
            $subkriteria_terpilih[$item->uuid_kriteria] = $item->uuid_subkriteria;
        }

        return view('admin.mahasiswa.edit', compact(
            'module',
            'mahasiswa',
            'kriteria',
            'subkriteria_terpilih'
        ));
    }

    public function update(UpdateMahasiswaRequest $update_mahasiswa_request, $params)
    {
        // Cari data mahasiswa
        $mahasiswa = Mahasiswa::where('uuid', $params)->firstOrFail();

        $newTranskrip = $mahasiswa->transkrip;
        $newKelakuan = $mahasiswa->kelakuan_baik;
        $newPernyataan = $mahasiswa->pernyataan_kesiapan;

        $oldTranskripPath = public_path('/public/mahasiswa/' . $mahasiswa->transkrip);
        $oldKelakuanPath = public_path('/public/mahasiswa/' . $mahasiswa->kelakuan_baik);
        $oldPernyataanPath = public_path('/public/mahasiswa/' . $mahasiswa->pernyataan_kesiapan);

        if ($update_mahasiswa_request->file('transkrip')) {
            $extension = $update_mahasiswa_request->file('transkrip')->extension();
            $newTranskrip = 'transkrip' . '-' . now()->timestamp . '.' . $extension;
            $update_mahasiswa_request->file('transkrip')->storeAs('public/mahasiswa', $newTranskrip);

            // Hapus foto lama
            if (File::exists($oldTranskripPath)) {
                File::delete($oldTranskripPath);
            }
        }

        if ($update_mahasiswa_request->file('kelakuan_baik')) {
            $extension = $update_mahasiswa_request->file('kelakuan_baik')->extension();
            $newKelakuan = 'kelakuan_baik' . '-' . now()->timestamp . '.' . $extension;
            $update_mahasiswa_request->file('kelakuan_baik')->storeAs('public/mahasiswa', $newKelakuan);

            // Hapus foto lama
            if (File::exists($oldKelakuanPath)) {
                File::delete($oldKelakuanPath);
            }
        }

        if ($update_mahasiswa_request->file('pernyataan_kesiapan')) {
            $extension = $update_mahasiswa_request->file('pernyataan_kesiapan')->extension();
            $newPernyataan = 'pernyataan_kesiapan' . '-' . now()->timestamp . '.' . $extension;
            $update_mahasiswa_request->file('pernyataan_kesiapan')->storeAs('public/mahasiswa', $newPernyataan);

            // Hapus foto lama
            if (File::exists($oldPernyataanPath)) {
                File::delete($oldPernyataanPath);
            }
        }

        // Update data mahasiswa
        $angkatan = Angkatan::where('status', 'Aktiv')->first();
        $mahasiswa->update([
            'uuid_angkatan' => $angkatan->uuid,
            'nim' => $update_mahasiswa_request->nim,
            'nama' => $update_mahasiswa_request->nama,
            'semester' => $update_mahasiswa_request->semester,
            'sks' => $update_mahasiswa_request->sks,
            'transkrip' => $newTranskrip,
            'kelakuan_baik' => $newKelakuan,
            'pernyataan_kesiapan' => $newPernyataan,
        ]);

        // Update penilaian per kriteria berdasarkan input subkriteria
        foreach ($update_mahasiswa_request->subkriteria ?? [] as $kriteria_uuid => $uuid_subkriteria) {
            Penilaian::updateOrCreate(
                [
                    'uuid_mahasiswa' => $mahasiswa->uuid,
                    'uuid_kriteria' => $kriteria_uuid,
                ],
                [
                    'uuid_subkriteria' => $uuid_subkriteria,
                ]
            );
        }

        return $this->sendResponse(null, 'Update data success');
    }

    public function delete($params)
    {
        $mahasiswa = Mahasiswa::where('uuid', $params)->first();

        $oldTranskripPath = public_path('/public/mahasiswa/' . $mahasiswa->transkrip);
        $oldKelakuanPath = public_path('/public/mahasiswa/' . $mahasiswa->kelakuan_baik);
        $oldPernyataanPath = public_path('/public/mahasiswa/' . $mahasiswa->pernyataan_kesiapan);

        if (File::exists($oldTranskripPath)) {
            File::delete($oldTranskripPath);
        }
        if (File::exists($oldKelakuanPath)) {
            File::delete($oldKelakuanPath);
        }
        if (File::exists($oldPernyataanPath)) {
            File::delete($oldPernyataanPath);
        }

        if ($mahasiswa) {
            // Hapus semua penilaian yang berkaitan dengan mahasiswa ini
            Penilaian::where('uuid_mahasiswa', $params)->delete();

            // Hapus data mahasiswa
            $mahasiswa->delete();

            return $this->sendResponse(null, 'Delete data success');
        } else {
            return $this->sendError('Data not found');
        }
    }

    public function konfirmasi($params)
    {
        try {
            $data = Mahasiswa::where('uuid', $params)->first();
            $data->status = "Terkonfirmasi";
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }

        return $this->sendResponse($data, 'Update data success');
    }

    // daftar user
    public function register_mahasiswa()
    {
        $module = 'Pendaftaran Mahasiswa';
        $kriteria = Kriteria::all();
        $kriteria->map(function ($item) {
            $sub_krtiteria = SubKriteria::where('uuid_kriteria', $item->uuid)->get();
            $item->subkriteria = $sub_krtiteria;

            return $item;
        });
        return view('mahasiswa.index', compact('module', 'kriteria'));
    }
}
