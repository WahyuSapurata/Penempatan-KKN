<?php

namespace App\Http\Controllers;

use App\Models\Angkatan;
use App\Models\Event;
use App\Models\Lokasi;
use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Http\Request;

class Dashboard extends BaseController
{
    public function index()
    {
        if (auth()->check()) {
            return redirect()->back();
        }
        return redirect()->route('login.login-akun');
    }

    public function dashboard_admin()
    {
        $module = 'Dashboard';
        $angkatan = Angkatan::where('status', 'Aktiv')->first();
        $mahasiswa = Mahasiswa::all();
        $lokasi = Lokasi::where('uuid_angkatan', $angkatan->uuid)->count();
        return view('dashboard.admin', compact('module', 'angkatan', 'mahasiswa', 'lokasi'));
    }
}
