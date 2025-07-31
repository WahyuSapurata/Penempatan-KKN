<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <base href="../../../">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}" />
    <title>{{ config('app.name') }} | {{ $module }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8" />
    <meta property="og:description"
        content="Arvala Mockup is a professional product mockup template designed for digital goods. Perfect for creators and entrepreneurs." />
    <meta name="keywords"
        content="product mockup template, digital product mockup, marketplace mockup design, sell mockup templates, professional product mockups, digital goods mockup, mockup for creators, product presentation template, high-quality mockup designs, downloadable mockup templates, creative product mockups, mockup marketplace template, sell digital mockups, product display templates, mockup design for entrepreneurs, digital asset mockups, customizable mockup templates, premium mockup designs, mockup for digital products, product visualization templates">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="Kilq - Professional Product Mockup Template for Digital Goods" />
    <meta property="og:description"
        content="Kilq is a dynamic product mockup template designed to help creators and entrepreneurs showcase their digital products with professional, high-quality mockups. Perfect for selling and presenting digital goods in a visually appealing way." />
    <meta property="og:url" content="https://arvalamockup.com/" />
    <meta property="og:site_name" content="Kilq Mockup" />
    <meta property="og:image" content="https://arvalamockup.com/logo_arvala.png" />
    <meta property="og:image:alt" content="Kilq Mockup Preview" />
    <link rel="canonical" href="https://arvalamockup.com/" />
    <meta name="google-site-verification" content="FnO2dJiuVYaHHdnvK8oXap9nXg8FuI7ayeh6i1J7nEE" />
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/custom/jquery-ui/jquery-ui.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
</head>
<!--end::Head-->
<!--begin::Body-->

<body data-kt-name="metronic" id="kt_body"
    class="app-blank app-blank bgi-size-cover bgi-position-center bgi-no-repeat">
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <!--begin::Authentication - Sign-in -->
        <div class="d-flex flex-column justify-content-center flex-lg-row flex-column-fluid">
            <!--begin::Body-->
            <div class="d-none d-lg-flex justify-content-center align-items-center w-50 p-10"
                style="background-color: #49aa81">
                <img src="{{ asset('LP2M.png') }}" style="width: 300px" alt="">
            </div>
            <!--begin::Body-->
            <!--begin::Aside-->
            <div class="d-flex flex-center w-50 p-10">
                <!--begin::Wrapper-->
                <div class="d-flex justify-content-between flex-column-fluid flex-column w-100">
                    <!--begin::Body-->
                    <div class="py-10 shadow-lg"
                        style="background: rgb(110 101 101 / 50%); padding: 15px; border-radius: 10px; height: 89vh; overflow-y: scroll;">
                        <div class="d-flex d-lg-none justify-content-center align-items-center">
                            <img src="{{ asset('LP2M.png') }}" style="width: 150px; margin-bottom: 10px"
                                alt="">
                        </div>
                        <!--begin::Form-->
                        <form class="form-data position-relative" enctype="multipart/form-data">
                            <div class="mb-10">
                                <label class="form-label">Nama Mahasiswa <span class="text-danger">*</span></label>
                                <input type="text" id="nama" class="form-control" name="nama">
                                <small class="text-danger nama_error"></small>
                            </div>

                            <div class="mb-10">
                                <label class="form-label">NIM <span class="text-danger">*</span></label>
                                <input type="number" id="nim" class="form-control" name="nim">
                                <small class="text-danger nim_error"></small>
                            </div>

                            <div class="mb-10">
                                <label class="form-label">Semester <span class="text-danger">*</span></label>
                                <input type="text" id="semester" class="form-control" name="semester">
                                <small class="text-danger semester_error"></small>
                            </div>

                            <div class="mb-10">
                                <label class="form-label">SKS <span class="text-danger">*</span></label>
                                <input type="number" id="sks" class="form-control" name="sks">
                                <small class="text-danger sks_error"></small>
                            </div>

                            <div class="mb-10">
                                <label class="form-label">Transkrip <span class="text-danger">*</span></label>
                                <input type="file" accept=".pdf" id="transkrip" class="form-control"
                                    name="transkrip">
                                <small class="text-danger transkrip_error"></small>
                            </div>

                            <div class="mb-10">
                                <label class="form-label">Surat Kelakuan Baik <span
                                        class="text-danger">*</span></label>
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
                                            <option value="" disabled selected>-- Pilih Fakultas --</option>
                                            @foreach ($krit->subkriteria as $sub)
                                                <option value="{{ $sub->uuid }}">{{ $sub->nama }}</option>
                                            @endforeach
                                        </select>
                                    @elseif ($krit->nama_kriteria == 'Jurusan')
                                        <select name="subkriteria[{{ $krit->uuid }}]"
                                            class="form-select jurusan-select" data-control="select2" required
                                            data-placeholder="Pilih Jurusan">
                                        </select>
                                    @else
                                        <select name="subkriteria[{{ $krit->uuid }}]" class="form-select"
                                            data-control="select2" required data-placeholder="Pilih Sub kriteria">
                                            <option value="" disabled selected>-- Pilih --</option>
                                            @foreach ($krit->subkriteria as $sub)
                                                <option value="{{ $sub->uuid }}">{{ $sub->nama }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            @endforeach

                            <!--begin::Submit button-->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <!--begin::Indicator label-->
                                    <span class="indicator-label">Kirim</span>
                                    <!--end::Indicator label-->
                                </button>
                            </div>
                            <!--end::Submit button-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Aside-->
        </div>
        <!--end::Authentication - Sign-in-->
    </div>
    <!--end::Root-->
    <!--end::Main-->
    <!--begin::Javascript-->
    <script>
        var hostUrl = "assets/";
    </script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
        crossorigin="anonymous"></script>
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="{{ asset('assets/plugins/custom/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script><!--begin::Custom Javascript(used by this page)-->
    <script src="{{ asset('assets/js/panel.js') }}"></script>
    <!--end::Custom Javascript-->
    @if ($message = Session::get('failed'))
        <script>
            swal.fire({
                title: "Eror",
                text: "{{ $message }}",
                icon: "warning",
                showConfirmButton: false,
                timer: 1500,
            });
        </script>
    @endif

    @if ($message = Session::get('success'))
        <script>
            swal.fire({
                title: "Sukses",
                text: "{{ $message }}",
                icon: "success",
                showConfirmButton: false,
                timer: 1500,
            });
        </script>
    @endif

    <script>
        $(document).ready(function() {
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
                    url: '/register-store',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $(".text-danger").html("");
                        if (response.success == true) {
                            swal
                                .fire({
                                    text: `Mahasiswa berhasil di Tambah`,
                                    icon: "success",
                                    showConfirmButton: true,
                                })
                                .then(function() {
                                    window.location.reload();
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

            $('.fakultas-select').on('change', function() {
                const fakultasNama = $(this).find('option:selected').text().trim();
                const jurusanYangCocok = dataFakultas.find(f => f.nama === fakultasNama)?.jurusan || [];

                const semuaSubkriteria = @json($kriteria->pluck('subkriteria')->flatten(1));

                let jurusanHtml = `<option selected disabled>-- Pilih Jurusan --</option>`;
                $.each(jurusanYangCocok, function(i, jurusan) {
                    // Cari subkriteria yang cocok dengan nama jurusan
                    const sub = semuaSubkriteria.find(s => s.nama === jurusan);
                    const uuid = sub ? sub.uuid : '';

                    jurusanHtml += `<option value="${uuid}">${jurusan}</option>`;
                });

                // Replace isi dropdown jurusan
                $('.jurusan-select').html(jurusanHtml);

                // Reinitialize select2 jika digunakan
                if ($('.jurusan-select').hasClass('select2-hidden-accessible')) {
                    $('.jurusan-select').select2();
                }
            });

        });
    </script>

</body>
<!--end::Body-->

</html>
