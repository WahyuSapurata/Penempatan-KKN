@extends('layouts.layout')
@section('button')
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        <!--begin::Page title-->
        <div data-kt-swapper="true" data-kt-swapper-mode="prepend"
            data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}"
            class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
            <!--begin::Title-->
            <button class="btn btn-info btn-sm" id="button-side-form">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" id="svg-button"
                    viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                    <style>
                        #svg-button {
                            fill: #ffffff
                        }
                    </style>
                    <path
                        d="M512 256A256 256 0 1 0 0 256a256 256 0 1 0 512 0zM217.4 376.9L117.5 269.8c-3.5-3.8-5.5-8.7-5.5-13.8s2-10.1 5.5-13.8l99.9-107.1c4.2-4.5 10.1-7.1 16.3-7.1c12.3 0 22.3 10 22.3 22.3l0 57.7 96 0c17.7 0 32 14.3 32 32l0 32c0 17.7-14.3 32-32 32l-96 0 0 57.7c0 12.3-10 22.3-22.3 22.3c-6.2 0-12.1-2.6-16.3-7.1z" />
                </svg>
                Kembali</button>
            <!--end::Title-->
        </div>
        <!--end::Page title-->
    </div>
@endsection
@section('content')
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <!--begin::Container-->
        <div id="kt_content_container" class="container">
            <div class="row">

                <div class="card">
                    <div class="card-body p-0">
                        <!--begin::Card body-->
                        <div class="card-body hover-scroll-overlay-y">
                            <form class="form-data" enctype="multipart/form-data">

                                <input type="hidden" name="id">
                                <input type="hidden" name="uuid">

                                <div class="mb-10">
                                    <label class="form-label">Nama Mahasiswa</label>
                                    <input type="text" id="nama" class="form-control" value="{{ $mahasiswa->nama }}"
                                        name="nama">
                                    <small class="text-danger nama_error"></small>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">NIM</label>
                                    <input type="number" id="nim" class="form-control" value="{{ $mahasiswa->nim }}"
                                        name="nim">
                                    <small class="text-danger nim_error"></small>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label d-block">Jenis Kelamin</label>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                            {{ $mahasiswa->jenis_kelamin == 'Laki-laki' ? 'checked' : '' }}
                                            name="jenis_kelamin" id="jenisLaki" value="Laki-laki" required>
                                        <label class="form-check-label" for="jenisLaki">Laki-laki</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio"
                                            {{ $mahasiswa->jenis_kelamin == 'Perempuan' ? 'checked' : '' }}
                                            name="jenis_kelamin" id="jenisPerempuan" value="Perempuan" required>
                                        <label class="form-check-label" for="jenisPerempuan">Perempuan</label>
                                    </div>

                                    <small class="text-danger jenis_kelamin_error"></small>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">Fakultas</label>
                                    <select name="fakultas" class="form-select" data-control="select2"
                                        id="from_select_fakultas" data-placeholder="Pilih jenis inputan">
                                    </select>
                                    <small class="text-danger fakultas_error"></small>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">Jurusan</label>
                                    <select name="jurusan" class="form-select" data-control="select2"
                                        id="from_select_jurusan" data-placeholder="Pilih jenis inputan">
                                    </select>
                                    <small class="text-danger jurusan_error"></small>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">Alamat</label>
                                    <input type="text" id="alamat" class="form-control"
                                        value="{{ $mahasiswa->alamat }}" name="alamat">
                                    <small class="text-danger alamat_error"></small>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">Angkatan</label>
                                    <select name="uuid_angkatan" class="form-select" data-control="select2"
                                        id="from_select_angkatan" data-placeholder="Pilih jenis inputan">
                                    </select>
                                    <small class="text-danger angkatan_error"></small>
                                </div>

                                @foreach ($kriteria as $krit)
                                    <div class="mb-4">
                                        <label class="form-label">
                                            {{ ucfirst($krit->nama_kriteria) }} <span class="text-danger">*</span>
                                        </label>
                                        <input type="number" class="form-control" name="kriteria[{{ $krit->uuid }}]"
                                            placeholder="Masukkan nilai (1-10)" min="1" max="10"
                                            value="{{ old('kriteria.' . $krit->uuid, $nilai_kriteria[$krit->uuid] ?? '') }}"
                                            oninput="this.value = Math.min(Math.max(this.value, 1), 10)" required>
                                    </div>
                                @endforeach

                                <div class="separator separator-dashed mt-8 mb-5"></div>
                                <div class="d-flex gap-5">
                                    <button type="submit"
                                        class="btn btn-success btn-sm btn-submit d-flex align-items-center"><i
                                            class="bi bi-file-earmark-diff"></i> Simpan</button>
                                </div>
                            </form>
                        </div>
                        <!--end::Card body-->
                    </div>
                </div>

            </div>
        </div>
        <!--end::Container-->
    </div>
@endsection
@section('script')
    <script>
        let control = new Control();

        var currentPath = window.location.pathname;
        var pathParts = currentPath.split('/'); // Membagi path menggunakan karakter '/'
        var lastPart = pathParts[pathParts.length - 1]; // Mengambil elemen terakhir dari array

        $(document).on('click', '#button-side-form', function() {
            window.history.back();
        })

        $(document).on('submit', ".form-data", function(e) {
            e.preventDefault();

            let form = $(this)[0];
            let formData = new FormData(form);

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                type: 'POST',
                url: '/admin/mahasiswa-update/' + lastPart,
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $(".text-danger").html("");
                    if (response.success == true) {
                        swal
                            .fire({
                                text: `Mahasiswa berhasil di Edit`,
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500,
                            })
                            .then(function() {
                                window.location.href = '/admin/mahasiswa';
                            });
                    } else {
                        $("form")[0].reset();
                        dropzone.removeAllFiles();
                        $("#from_select").val(null).trigger("change");
                        swal.fire({
                            title: response.message,
                            text: response.data,
                            icon: "warning",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    }
                },
                error: function(xhr) {
                    $(".text-danger").html("");
                    $.each(xhr.responseJSON["errors"], function(key, value) {
                        $(`.${key}_error`).html(value);
                    });
                },
            });
        });

        const dataFakultas = [{
                nama: "Syariah dan Hukum",
                jurusan: [
                    "Hukum Keluarga (Ahwal Syakhshiyyah)",
                    "Hukum Tata Negara (Siyasah)",
                    "Hukum Ekonomi Syariah (Muamalah)",
                    "Ilmu Hukum",
                    "Perbandingan Mazhab dan Hukum"
                ]
            },
            {
                nama: "Ushuluddin dan Filsafat",
                jurusan: [
                    "Akidah dan Filsafat Islam",
                    "Ilmu Al-Qur'an dan Tafsir",
                    "Ilmu Hadis",
                    "Studi Agama-Agama"
                ]
            },
            {
                nama: "Tarbiyah dan Keguruan",
                jurusan: [
                    "Pendidikan Agama Islam",
                    "Pendidikan Bahasa Arab",
                    "Manajemen Pendidikan Islam",
                    "Pendidikan Guru Madrasah Ibtidaiyah (PGMI)",
                    "Pendidikan Islam Anak Usia Dini (PIAUD)",
                    "Tadris Bahasa Inggris",
                    "Tadris Ilmu Pengetahuan Alam",
                    "Tadris Matematika",
                    "Tadris Biologi",
                    "Tadris Fisika",
                    "Tadris Kimia",
                    "Pendidikan Profesi Guru (PPG)"
                ]
            },
            {
                nama: "Ekonomi dan Bisnis Islam",
                jurusan: [
                    "Ekonomi Syariah",
                    "Perbankan Syariah",
                    "Akuntansi Syariah",
                    "Manajemen Zakat dan Wakaf"
                ]
            },
            {
                nama: "Dakwah dan Komunikasi",
                jurusan: [
                    "Komunikasi dan Penyiaran Islam",
                    "Bimbingan dan Konseling Islam",
                    "Manajemen Dakwah",
                    "Pengembangan Masyarakat Islam",
                    "Ilmu Komunikasi",
                    "Jurnalistik"
                ]
            },
            {
                nama: "Sains dan Teknologi",
                jurusan: [
                    "Teknik Informatika",
                    "Sistem Informasi",
                    "Teknik Arsitektur",
                    "Teknik Lingkungan",
                    "Matematika",
                    "Biologi",
                    "Fisika",
                    "Kimia"
                ]
            },
            {
                nama: "Kedokteran dan Ilmu Kesehatan",
                jurusan: [
                    "Pendidikan Dokter",
                    "Kesehatan Masyarakat",
                    "Ilmu Keperawatan",
                    "Farmasi",
                    "Profesi Dokter",
                    "Profesi Ners"
                ]
            },
            {
                nama: "Adab dan Humaniora",
                jurusan: [
                    "Bahasa dan Sastra Arab",
                    "Sejarah dan Kebudayaan Islam",
                    "Ilmu Perpustakaan",
                    "Bahasa dan Sastra Inggris"
                ]
            }
        ];

        function push_select_fakultas(selectedFakultas = null, selectedJurusan = null) {
            let html = `<option disabled ${selectedFakultas === null ? 'selected' : ''}>Pilih jenis inputan</option>`;

            $.each(dataFakultas, function(index, fakultas) {
                html +=
                    `<option value="${index}" ${selectedFakultas == index ? 'selected' : ''}>${fakultas.nama}</option>`;
            });

            $('#from_select_fakultas').html(html);

            // Reset jurusan jika fakultas belum dipilih
            if (selectedFakultas !== null) {
                let jurusanHtml =
                    `<option disabled ${selectedJurusan === null ? 'selected' : ''}>Pilih jenis inputan</option>`;
                $.each(dataFakultas[selectedFakultas].jurusan, function(i, jurusan) {
                    jurusanHtml +=
                        `<option value="${jurusan}" ${selectedJurusan == jurusan ? 'selected' : ''}>${jurusan}</option>`;
                });
                $('#from_select_jurusan').html(jurusanHtml);
            } else {
                $('#from_select_jurusan').html(`<option selected disabled>Pilih jenis inputan</option>`);
            }
        }

        // Ketika fakultas berubah, isi jurusan
        $('#from_select_fakultas').on('change', function() {
            const index = $(this).val();
            let jurusanHtml = `<option selected disabled>Pilih jenis inputan</option>`;
            $.each(dataFakultas[index].jurusan, function(i, jurusan) {
                jurusanHtml += `<option value="${jurusan}">${jurusan}</option>`;
            });
            $('#from_select_jurusan').html(jurusanHtml);
        });



        function push_select_angkatan() {
            $.ajax({
                url: "{{ route('admin.angkatan-get') }}",
                method: "GET",
                success: function(res) {

                    $('#from_select_angkatan').html("");
                    let html = "<option selected disabled>Pilih jenis inputan</option>";
                    $.each(res.data, function(x, y) {
                        let isSelected = y.uuid == "{{ $mahasiswa->uuid_angkatan }}" ? "selected" : "";
                        html += `<option value="${y.uuid}" ${isSelected}>${y.angkatan}</option>`;
                    });
                    $('#from_select_angkatan').html(html);
                },
                error: function(xhr) {
                    alert("gagal");
                },
            });
        }

        const selectedFakultasIndex = "{{ $mahasiswa->fakultas }}";
        const selectedJurusan = "{{ $mahasiswa->jurusan }}";

        // Inisialisasi saat halaman siap
        $(function() {
            push_select_fakultas(selectedFakultasIndex, selectedJurusan);
            push_select_angkatan();
        });
    </script>
@endsection
