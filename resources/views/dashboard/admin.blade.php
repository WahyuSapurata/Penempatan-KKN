@extends('layouts.layout')
@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <div class="row">
                <div class="col-lg-3 col-6 mb-4">
                    <div class="card text-white bg-info">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="text-white">{{ $angkatan ? $angkatan->angkatan : 'Non Aktiv' }}</h3>
                                <p class="mb-0">Angkatan</p>
                            </div>
                            <i class="bi bi-calendar3 fs-1 text-white"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6 mb-4">
                    <div class="card text-white bg-success">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="text-white">{{ $mahasiswa->where('status', 'Belum Diverifikasi')->count() }} <sup
                                        style="font-size: 13px">Mahasiswa</sup></h3>
                                <p class="mb-0">Belum Aprove</p>
                            </div>
                            <i class="bi bi-person-workspace fs-1 text-white"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6 mb-4">
                    <div class="card text-white bg-warning">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="text-white">{{ $mahasiswa->where('status', 'Terkonfirmasi')->count() }} <sup
                                        style="font-size: 13px">Mahasiswa</sup></h3>
                                <p class="mb-0">Terkonfimasi</p>
                            </div>
                            <i class="bi bi-people-fill fs-1 text-white"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6 mb-4">
                    <div class="card text-white bg-danger">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="text-white">{{ $lokasi }}</h3>
                                <p class="mb-0">Total Lokasi</p>
                            </div>
                            <i class="bi bi-pin-map-fill fs-1 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!--end::Container-->
    </div>
@endsection
