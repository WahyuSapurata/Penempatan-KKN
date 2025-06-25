<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKriteriaRequest;
use App\Http\Requests\UpdateKriteriaRequest;
use App\Models\Kriteria;
use App\Models\SubKriteria;

class KriteriaController extends BaseController
{
    public function index()
    {
        $module = 'Kriteria';
        return view('admin.kriteria.index', compact('module'));
    }

    public function get()
    {
        $data = Kriteria::latest()->get();
        $data->map(function ($item) {
            $sub_krtiteria = SubKriteria::where('uuid_kriteria', $item->uuid)->get();
            $item->subkriteria = $sub_krtiteria;

            return $item;
        });
        return $this->sendResponse($data, 'Get data success');
    }

    public function add(StoreKriteriaRequest $storeKriteriaRequest)
    {
        $data = array();
        try {
            $data = new Kriteria();
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
