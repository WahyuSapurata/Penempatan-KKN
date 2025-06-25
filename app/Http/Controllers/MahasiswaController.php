<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Models\Kriteria;
use App\Models\Mahasiswa;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use Illuminate\Http\Request;

class MahasiswaController extends BaseController
{
    public function index()
    {
        $module = 'Mahasiswa';
        return view('admin.mahasiswa.index', compact('module'));
    }

    public function get($params)
    {
        $mahasiswa = Mahasiswa::where('uuid_angkatan', $params)->get();
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
        $mahasiswa = Mahasiswa::create([
            'nim' => $store_mahasiswa_request->nim,
            'nama' => $store_mahasiswa_request->nama,
            'jenis_kelamin' => $store_mahasiswa_request->jenis_kelamin,
            'fakultas' => $store_mahasiswa_request->fakultas,
            'jurusan' => $store_mahasiswa_request->jurusan,
            'alamat' => $store_mahasiswa_request->alamat,
            'uuid_angkatan' => $store_mahasiswa_request->uuid_angkatan,
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

    public function update(StoreMahasiswaRequest $update_mahasiswa_request, $params)
    {
        // Cari data mahasiswa
        $mahasiswa = Mahasiswa::where('uuid', $params)->firstOrFail();

        // Update data mahasiswa
        $mahasiswa->update([
            'nim' => $update_mahasiswa_request->nim,
            'nama' => $update_mahasiswa_request->nama,
            'jenis_kelamin' => $update_mahasiswa_request->jenis_kelamin,
            'fakultas' => $update_mahasiswa_request->fakultas,
            'jurusan' => $update_mahasiswa_request->jurusan,
            'alamat' => $update_mahasiswa_request->alamat,
            'uuid_angkatan' => $update_mahasiswa_request->uuid_angkatan,
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
}
