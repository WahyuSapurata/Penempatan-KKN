<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAngkatanRequest;
use App\Http\Requests\UpdateAngkatanRequest;
use App\Models\Angkatan;

class AngkatanController extends BaseController
{
    public function index()
    {
        $module = 'Angkatan';
        return view('admin.angkatan.index', compact('module'));
    }

    public function get()
    {
        $data = Angkatan::all();
        return $this->sendResponse($data, 'Get data success');
    }

    public function add(StoreAngkatanRequest $storeAngkatanRequest)
    {
        // Cek jika request status adalah 'Aktiv'
        if ($storeAngkatanRequest->status === 'Aktiv') {
            $angkatanAktif = Angkatan::where('status', 'Aktiv')->first();
            if ($angkatanAktif) {
                return $this->sendError(
                    'error',
                    'Angkatan ' . $angkatanAktif->angkatan . ' statusnya aktiv, hanya satu angkatan yang boleh aktiv.',
                    400
                );
            }
        }

        $data = array();
        try {
            $data = new Angkatan();
            $data->angkatan = $storeAngkatanRequest->angkatan;
            $data->status = $storeAngkatanRequest->status;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Add data success');
    }

    public function show($params)
    {
        $data = array();
        try {
            $data = Angkatan::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function edit(StoreAngkatanRequest $storeAngkatanRequest, $params)
    {
        $data = Angkatan::where('uuid', $params)->first();

        if ($storeAngkatanRequest->status === 'Aktiv') {
            $angkatanAktif = Angkatan::where('status', 'Aktiv')->first();
            if ($angkatanAktif) {
                return $this->sendError(
                    'error',
                    'Angkatan ' . $angkatanAktif->angkatan . ' statusnya aktiv, hanya satu angkatan yang boleh aktiv.',
                    400
                );
            }
        }

        try {
            $data->angkatan = $storeAngkatanRequest->angkatan;
            $data->status = $storeAngkatanRequest->status;
            $data->save();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Add data success');
    }

    public function delete($params)
    {
        $data = array();
        try {
            $data = Angkatan::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete data success');
    }
}
