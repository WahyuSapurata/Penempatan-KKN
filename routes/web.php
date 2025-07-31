<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::get('/', 'Dashboard@index')->name('home.index');

    Route::get('/register', 'MahasiswaController@register_mahasiswa')->name('register');
    Route::post('/register-store', 'MahasiswaController@store')->name('register-store');

    Route::group(['prefix' => 'login', 'middleware' => ['guest'], 'as' => 'login.'], function () {
        Route::get('/login-akun', 'Auth@show')->name('login-akun');
        Route::post('/login-proses', 'Auth@login_proses')->name('login-proses');
    });

    Route::group(['prefix' => 'admin', 'middleware' => ['auth'], 'as' => 'admin.'], function () {
        Route::get('/dashboard-admin', 'Dashboard@dashboard_admin')->name('dashboard-admin');

        Route::get('/angkatan', 'AngkatanController@index')->name('angkatan');
        Route::get('/angkatan-get', 'AngkatanController@get')->name('angkatan-get');
        Route::post('/angkatan-add', 'AngkatanController@add')->name('angkatan-add');
        Route::get('/angkatan-show/{params}', 'AngkatanController@show')->name('angkatan-show');
        Route::post('/angkatan-edit/{params}', 'AngkatanController@edit')->name('angkatan-edit');
        Route::delete('/angkatan-delete/{params}', 'AngkatanController@delete')->name('angkatan-delete');

        Route::get('/lokasi', 'LokasiController@index')->name('lokasi');
        Route::get('/lokasi-get', 'LokasiController@get')->name('lokasi-get');
        Route::post('/lokasi-add', 'LokasiController@add')->name('lokasi-add');
        Route::get('/lokasi-show/{params}', 'LokasiController@show')->name('lokasi-show');
        Route::post('/lokasi-edit/{params}', 'LokasiController@edit')->name('lokasi-edit');
        Route::delete('/lokasi-delete/{params}', 'LokasiController@delete')->name('lokasi-delete');

        Route::get('/kriteria', 'KriteriaController@index')->name('kriteria');
        Route::get('/kriteria-get', 'KriteriaController@get')->name('kriteria-get');
        Route::post('/kriteria-add', 'KriteriaController@add')->name('kriteria-add');
        Route::get('/kriteria-show/{params}', 'KriteriaController@show')->name('kriteria-show');
        Route::post('/kriteria-edit/{params}', 'KriteriaController@edit')->name('kriteria-edit');
        Route::delete('/kriteria-delete/{params}', 'KriteriaController@delete')->name('kriteria-delete');

        Route::get('/sub-kriteria/{params}', 'SubKriteriaController@index')->name('sub-kriteria');
        Route::get('/sub-kriteria-get/{params}', 'SubKriteriaController@get')->name('sub-kriteria-get');
        Route::post('/sub-kriteria-add', 'SubKriteriaController@add')->name('sub-kriteria-add');
        Route::get('/sub-kriteria-show/{params}', 'SubKriteriaController@show')->name('sub-kriteria-show');
        Route::post('/sub-kriteria-edit/{params}', 'SubKriteriaController@edit')->name('sub-kriteria-edit');
        Route::delete('/sub-kriteria-delete/{params}', 'SubKriteriaController@delete')->name('sub-kriteria-delete');

        Route::get('/mahasiswa', 'MahasiswaController@index')->name('mahasiswa');
        Route::get('/mahasiswa-get', 'MahasiswaController@get')->name('mahasiswa-get');
        Route::get('/mahasiswa-add', 'MahasiswaController@add')->name('mahasiswa-add');
        Route::post('/mahasiswa-store', 'MahasiswaController@store')->name('mahasiswa-store');
        Route::get('/mahasiswa-edit/{params}', 'MahasiswaController@edit')->name('mahasiswa-edit');
        Route::post('/mahasiswa-update/{params}', 'MahasiswaController@update')->name('mahasiswa-update');
        Route::delete('/mahasiswa-delete/{params}', 'MahasiswaController@delete')->name('mahasiswa-delete');
        Route::post('/mahasiswa-konfirmasi/{params}', 'MahasiswaController@konfirmasi')->name('mahasiswa-konfirmasi');

        Route::get('/penilaian', 'PenilaianController@index')->name('penilaian');
        Route::get('/penilaian-get', 'PenilaianController@proses')->name('penilaian-get');
        Route::get('/penilaian-export', 'PenilaianController@export')->name('penilaian-export');
    });

    Route::get('/logout', 'Auth@logout')->name('logout');
});
