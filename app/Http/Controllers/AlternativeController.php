<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAlternativeRequest;
use App\Http\Requests\UpdateAlternativeRequest;
use App\Models\Alternative;

class AlternativeController extends BaseController
{
    public function index()
    {
        $module = 'Alternative';
        return view('admin.alternative.index', compact('module'));
    }

    public function get()
    {
        $data = Alternative::all();
        return $this->sendResponse($data, 'Get data success');
    }

    public function add(StoreAlternativeRequest $storeAlternativeRequest)
    {
        $data = array();
        try {
            $data = new Alternative();
            $data->nama_lokasi = $storeAlternativeRequest->nama_lokasi;
            $data->kuota = $storeAlternativeRequest->kuota;
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
            $data = Alternative::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function edit(StoreAlternativeRequest $storeAlternativeRequest, $params)
    {
        $data = Alternative::where('uuid', $params)->first();
        try {
            $data->nama_lokasi = $storeAlternativeRequest->nama_lokasi;
            $data->kuota = $storeAlternativeRequest->kuota;
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
            $data = Alternative::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete Event success');
    }
}
