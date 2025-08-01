<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLokasiRequest;
use App\Http\Requests\UpdateLokasiRequest;
use App\Models\Angkatan;
use App\Models\Lokasi;

class LokasiController extends BaseController
{
    public function index()
    {
        $module = 'Lokasi';
        $angkatan = Angkatan::where('status', 'Aktiv')->first();
        if (!$angkatan) {
            return redirect()->route('admin.angkatan')->with('failed', 'Angkatan aktiv belum di tentukan');
        }
        return view('admin.lokasi.index', compact('module'));
    }

    public function get()
    {
        $angkatan = Angkatan::where('status', 'Aktiv')->first();
        $data = Lokasi::where('uuid_angkatan', $angkatan->uuid)->get();
        return $this->sendResponse($data, 'Get data success');
    }

    public function add(StoreLokasiRequest $storeLokasiRequest)
    {
        $data = array();
        try {
            $angkatan = Angkatan::where('status', 'Aktiv')->first();

            $data = new Lokasi();
            $data->uuid_angkatan = $angkatan->uuid;
            $data->lokasi = $storeLokasiRequest->lokasi;
            $data->kuota = $storeLokasiRequest->kuota;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Add event success');
    }

    public function show($params)
    {
        $data = array();
        try {
            $data = Lokasi::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function edit(StoreLokasiRequest $storeLokasiRequest, $params)
    {
        $data = Lokasi::where('uuid', $params)->first();
        try {
            $data->lokasi = $storeLokasiRequest->lokasi;
            $data->kuota = $storeLokasiRequest->kuota;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Add event success');
    }

    public function delete($params)
    {
        $data = array();
        try {
            $data = Lokasi::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete Event success');
    }
}
