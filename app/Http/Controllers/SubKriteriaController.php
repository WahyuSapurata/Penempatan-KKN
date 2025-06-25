<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubKriteriaRequest;
use App\Http\Requests\UpdateSubKriteriaRequest;
use App\Models\Kriteria;
use App\Models\SubKriteria;

class SubKriteriaController extends BaseController
{
    public function index($params)
    {
        $kriteria = Kriteria::where('uuid', $params)->first();
        $module = 'Sub Kriteria ' . $kriteria->nama_kriteria;
        return view('admin.subkriteria.index', compact('module', 'kriteria'));
    }

    public function get($params)
    {
        $data = SubKriteria::where('uuid_kriteria', $params)->get();
        return $this->sendResponse($data, 'Get data success');
    }

    public function add(StoreSubKriteriaRequest $store_sub_kriteria_request)
    {
        $data = array();
        try {
            $data = new SubKriteria();
            $data->uuid_kriteria = $store_sub_kriteria_request->uuid_kriteria;
            $data->nama = $store_sub_kriteria_request->nama;
            $data->bobot = $store_sub_kriteria_request->bobot;
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
            $data = SubKriteria::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function edit(StoreSubKriteriaRequest $store_sub_kriteria_request, $params)
    {
        $data = SubKriteria::where('uuid', $params)->first();
        try {
            $data->uuid_kriteria = $store_sub_kriteria_request->uuid_kriteria;
            $data->nama = $store_sub_kriteria_request->nama;
            $data->bobot = $store_sub_kriteria_request->bobot;
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
            $data = SubKriteria::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete Event success');
    }
}
