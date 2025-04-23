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
        $data = array();
        try {
            $data = new Angkatan();
            $data->angkatan = $storeAngkatanRequest->angkatan;
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
            $data = Angkatan::where('uuid', $params)->first();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Show data success');
    }

    public function edit(StoreAngkatanRequest $storeAngkatanRequest, $params)
    {
        $data = Angkatan::where('uuid', $params)->first();
        try {
            $data->angkatan = $storeAngkatanRequest->angkatan;
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
            $data = Angkatan::where('uuid', $params)->first();
            $data->delete();
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), $e->getMessage(), 400);
        }
        return $this->sendResponse($data, 'Delete Event success');
    }
}
