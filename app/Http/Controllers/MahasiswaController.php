<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMahasiswaRequest;
use App\Http\Requests\UpdateMahasiswaRequest;
use App\Models\Kriteria;
use App\Models\Mahasiswa;
use App\Models\Penilaian;
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

        // Simpan penilaian per kriteria
        foreach ($store_mahasiswa_request->kriteria as $kriteria_uuid => $nilai) {
            Penilaian::create([
                'uuid_mahasiswa' => $mahasiswa->uuid,
                'uuid_kriteria' => $kriteria_uuid,
                'nilai' => $nilai,
            ]);
        }

        return $this->sendResponse(null, 'Add data success');
    }

    public function edit($params)
    {
        $module = 'Edit Mahasiswa';
        $mahasiswa = Mahasiswa::where('uuid', $params)->first();

        $kriteria = Kriteria::all();
        $penilaian = Penilaian::where('uuid_mahasiswa', $params)->get();

        // Buat array [uuid_kriteria => nilai]
        $nilai_kriteria = [];
        foreach ($penilaian as $item) {
            $nilai_kriteria[$item->uuid_kriteria] = $item->nilai;
        }

        return view('admin.mahasiswa.edit', compact('module', 'mahasiswa', 'kriteria', 'penilaian', 'nilai_kriteria'));
    }

    public function update(StoreMahasiswaRequest $update_mahasiswa_request, $params)
    {
        $mahasiswa = Mahasiswa::where('uuid', $params)->first();
        $mahasiswa->update([
            'nim' => $update_mahasiswa_request->nim,
            'nama' => $update_mahasiswa_request->nama,
            'jenis_kelamin' => $update_mahasiswa_request->jenis_kelamin,
            'fakultas' => $update_mahasiswa_request->fakultas,
            'jurusan' => $update_mahasiswa_request->jurusan,
            'alamat' => $update_mahasiswa_request->alamat,
            'uuid_angkatan' => $update_mahasiswa_request->uuid_angkatan,
        ]);

        // Update penilaian per kriteria
        foreach ($update_mahasiswa_request->kriteria as $kriteria_uuid => $nilai) {
            Penilaian::updateOrCreate(
                [
                    'uuid_mahasiswa' => $params,
                    'uuid_kriteria' => $kriteria_uuid,
                ],
                [
                    'nilai' => $nilai,
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
