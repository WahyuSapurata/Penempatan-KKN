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

                                <div class="mb-10">
                                    <label class="form-label">Semester <span class="text-danger">*</span></label>
                                    <input type="text" id="semester" class="form-control"
                                        value="{{ $mahasiswa->semester }}" name="semester">
                                    <small class="text-danger semester_error"></small>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">SKS <span class="text-danger">*</span></label>
                                    <input type="number" id="sks" class="form-control" value="{{ $mahasiswa->sks }}"
                                        name="sks">
                                    <small class="text-danger sks_error"></small>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">Transkrip <span class="text-danger">*</span></label>
                                    <input type="file" accept=".pdf" id="transkrip" class="form-control"
                                        name="transkrip">
                                    <small class="text-danger transkrip_error"></small>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">Surat Kelakuan Baik <span class="text-danger">*</span></label>
                                    <input type="file" accept=".pdf" id="kelakuan_baik" class="form-control"
                                        name="kelakuan_baik">
                                    <small class="text-danger kelakuan_baik_error"></small>
                                </div>

                                <div class="mb-10">
                                    <label class="form-label">Surat Pernyataan Kesiapan <span
                                            class="text-danger">*</span></label>
                                    <input type="file" accept=".pdf" id="pernyataan_kesiapan" class="form-control"
                                        name="pernyataan_kesiapan">
                                    <small class="text-danger pernyataan_kesiapan_error"></small>
                                </div>

                                @foreach ($kriteria as $krit)
                                    <div class="mb-4">
                                        <label class="form-label">
                                            {{ ucfirst($krit->nama_kriteria) }} <span class="text-danger">*</span>
                                        </label>

                                        @if ($krit->nama_kriteria == 'Fakultas')
                                            <select name="subkriteria[{{ $krit->uuid }}]"
                                                class="form-select fakultas-select" data-control="select2" required
                                                data-placeholder="Pilih Fakultas">
                                                <option value="" disabled
                                                    {{ empty(old('subkriteria.' . $krit->uuid)) ? 'selected' : '' }}>--
                                                    Pilih --</option>

                                                @foreach ($krit->subkriteria as $sub)
                                                    <option value="{{ $sub->uuid }}" data-nama="{{ trim($sub->nama) }}"
                                                        {{ old('subkriteria.' . $krit->uuid, $subkriteria_terpilih[$krit->uuid] ?? '') == $sub->uuid ? 'selected' : '' }}>
                                                        {{ $sub->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @elseif ($krit->nama_kriteria == 'Jurusan')
                                            <select name="subkriteria[{{ $krit->uuid }}]"
                                                class="form-select jurusan-select" data-control="select2" required
                                                data-placeholder="Pilih Jurusan">
                                                <option value="" disabled
                                                    {{ empty(old('subkriteria.' . $krit->uuid)) ? 'selected' : '' }}>--
                                                    Pilih --</option>

                                                @foreach ($krit->subkriteria as $sub)
                                                    <option value="{{ $sub->uuid }}"
                                                        data-nama="{{ trim($sub->nama) }}"
                                                        {{ old('subkriteria.' . $krit->uuid, $subkriteria_terpilih[$krit->uuid] ?? '') == $sub->uuid ? 'selected' : '' }}>
                                                        {{ $sub->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select name="subkriteria[{{ $krit->uuid }}]" class="form-select"
                                                data-control="select2" required data-placeholder="Pilih Sub kriteria">
                                                <option value="" disabled
                                                    {{ empty(old('subkriteria.' . $krit->uuid)) ? 'selected' : '' }}>
                                                    -- Pilih --
                                                </option>
                                                @foreach ($krit->subkriteria as $sub)
                                                    <option value="{{ $sub->uuid }}"
                                                        data-nama="{{ trim($sub->nama) }}"
                                                        {{ old('subkriteria.' . $krit->uuid, $subkriteria_terpilih[$krit->uuid] ?? '') == $sub->uuid ? 'selected' : '' }}>
                                                        {{ $sub->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif
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
                jurusan: ["Hukum Keluarga (Ahwal Syakhshiyyah)", "Hukum Tata Negara (Siyasah)",
                    "Hukum Ekonomi Syariah (Muamalah)", "Ilmu Hukum", "Perbandingan Mazhab dan Hukum"
                ]
            },
            {
                nama: "Ushuluddin dan Filsafat",
                jurusan: ["Akidah dan Filsafat Islam", "Ilmu Al-Qur'an dan Tafsir", "Ilmu Hadis", "Studi Agama-Agama"]
            },
            {
                nama: "Tarbiyah dan Keguruan",
                jurusan: ["Pendidikan Agama Islam", "Pendidikan Bahasa Arab", "Manajemen Pendidikan Islam",
                    "Pendidikan Guru Madrasah Ibtidaiyah (PGMI)", "Pendidikan Islam Anak Usia Dini (PIAUD)",
                    "Tadris Bahasa Inggris", "Tadris Ilmu Pengetahuan Alam", "Tadris Matematika", "Tadris Biologi",
                    "Tadris Fisika", "Tadris Kimia", "Pendidikan Profesi Guru (PPG)"
                ]
            },
            {
                nama: "Ekonomi dan Bisnis Islam",
                jurusan: ["Ekonomi Syariah", "Perbankan Syariah", "Akuntansi Syariah", "Manajemen Zakat dan Wakaf"]
            },
            {
                nama: "Dakwah dan Komunikasi",
                jurusan: ["Komunikasi dan Penyiaran Islam", "Bimbingan dan Konseling Islam", "Manajemen Dakwah",
                    "Pengembangan Masyarakat Islam", "Ilmu Komunikasi", "Jurnalistik"
                ]
            },
            {
                nama: "Sains dan Teknologi",
                jurusan: ["Teknik Informatika", "Sistem Informasi", "Teknik Arsitektur", "Teknik Lingkungan",
                    "Matematika", "Biologi", "Fisika", "Kimia"
                ]
            },
            {
                nama: "Kedokteran dan Ilmu Kesehatan",
                jurusan: ["Pendidikan Dokter", "Kesehatan Masyarakat", "Ilmu Keperawatan", "Farmasi", "Profesi Dokter",
                    "Profesi Ners"
                ]
            },
            {
                nama: "Adab dan Humaniora",
                jurusan: ["Bahasa dan Sastra Arab", "Sejarah dan Kebudayaan Islam", "Ilmu Perpustakaan",
                    "Bahasa dan Sastra Inggris"
                ]
            }
        ];

        $(document).ready(function() {
            const $fakultasSelect = $('.fakultas-select');
            const $jurusanSelect = $('.jurusan-select');

            // Simpan semua option jurusan dari HTML (saat load awal)
            const allJurusanOptions = $jurusanSelect.find('option').filter(function() {
                return $(this).val() !== '';
            }).clone();

            $fakultasSelect.on('change', function() {
                const selectedFakultas = $fakultasSelect.find('option:selected').data('nama')?.trim();
                const jurusanList = dataFakultas.find(f => f.nama === selectedFakultas)?.jurusan || [];

                // Kosongkan jurusan saat fakultas diubah
                $jurusanSelect.empty().append('<option value="" disabled selected>-- Pilih --</option>');

                // Filter ulang jurusan berdasarkan nama yang cocok
                jurusanList.forEach(function(jurusanNama) {
                    const $matchedOption = allJurusanOptions.filter(function() {
                        return $(this).data('nama')?.trim() === jurusanNama;
                    }).first();

                    if ($matchedOption.length) {
                        $jurusanSelect.append($matchedOption.clone());
                    }
                });

                $jurusanSelect.val('').trigger('change');

                // Reinitialize select2 jika digunakan
                if ($jurusanSelect.hasClass('select2-hidden-accessible')) {
                    $jurusanSelect.select2();
                }
            });

            // Jangan auto-trigger di sini (biarkan default muncul dari Blade saat load)
        });
    </script>
@endsection
