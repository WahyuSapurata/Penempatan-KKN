<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKriteriaRequest;
use App\Http\Requests\UpdateKriteriaRequest;
use App\Models\Angkatan;
use App\Models\Kriteria;
use App\Models\SubKriteria;

class KriteriaController extends BaseController
{
    public function index()
    {
        $module = 'Kriteria';
        $angkatan = Angkatan::where('status', 'Aktiv')->first();
        if (!$angkatan) {
            return redirect()->route('admin.angkatan')->with('failed', 'Angkatan aktiv belum di tentukan');
        }
        return view('admin.kriteria.index', compact('module'));
    }

    public function get()
    {
        $angkatan = Angkatan::where('status', 'Aktiv')->first();

        $data = Kriteria::where(function ($query) use ($angkatan) {
            $query->where('uuid_angkatan', $angkatan->uuid)
                ->orWhereNull('uuid_angkatan');
        })
            ->latest()
            ->get();

        $data->each(function ($item) {
            $item->subkriteria = SubKriteria::where('uuid_kriteria', $item->uuid)->get();
        });

        return $this->sendResponse($data, 'Get data success');
    }

    public function add(StoreKriteriaRequest $storeKriteriaRequest)
    {
        $data = array();
        try {
            $angkatan = Angkatan::where('status', 'Aktiv')->first();

            $data = new Kriteria();
            $data->uuid_angkatan = $angkatan->uuid;
            $data->nama_kriteria = $storeKriteriaRequest->nama_kriteria;
            $data->bobot = $storeKriteriaRequest->bobot;
            $data->jenis = $storeKriteriaRequest->jenis;
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
            $data = Kriteria::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function edit(StoreKriteriaRequest $storeKriteriaRequest, $params)
    {
        $data = Kriteria::where('uuid', $params)->first();
        try {
            $data->nama_kriteria = $storeKriteriaRequest->nama_kriteria;
            $data->bobot = $storeKriteriaRequest->bobot;
            $data->jenis = $storeKriteriaRequest->jenis;
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
            $data = Kriteria::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete Event success');
    }
}
