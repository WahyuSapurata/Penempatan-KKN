<?php

namespace App\Http\Controllers;

use App\Models\Angkatan;
use App\Services\PrometheeService;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PenilaianController extends BaseController
{
    public function index()
    {
        $module = 'Hasil Penilaian';
        $angkatan = Angkatan::where('status', 'Aktiv')->first();
        if (!$angkatan) {
            return redirect()->route('admin.angkatan')->with('failed', 'Angkatan aktiv belum di tentukan');
        }

        $result = (new PrometheeService())->proses();

        if (!$result || !is_array($result)) {
            return $this->sendError('Gagal memproses data.', [], 400);
        }

        $flattened = [];
        $jumlahPerLokasi = [];

        foreach ($result as $lokasi) {
            $namaLokasi = $lokasi['nama_lokasi'] ?? '-';
            $mahasiswaList = $lokasi['mahasiswa'] ?? [];

            // Hitung jumlah mahasiswa per lokasi
            $jumlahPerLokasi[$namaLokasi] = count($mahasiswaList);

            foreach ($mahasiswaList as $mhs) {
                $flattened[] = [
                    'uuid' => $mhs['uuid'],
                    'nama' => $mhs['nama'],
                    'jurusan' => $mhs['jurusan'],
                    'net_flow' => $mhs['net_flow'],
                    'nama_lokasi' => $namaLokasi,
                    'nim' => $mhs['nim'],
                ];
            }
        }

        return view('admin.hasil.index', compact('module', 'jumlahPerLokasi'));
    }

    public function proses()
    {
        try {
            $result = (new PrometheeService())->proses();

            if (!$result || !is_array($result)) {
                return $this->sendError('Gagal memproses data.', [], 400);
            }

            $flattened = [];

            foreach ($result as $lokasi) {
                foreach ($lokasi['mahasiswa'] as $mhs) {
                    $flattened[] = [
                        'uuid' => $mhs['uuid'],
                        'nama' => $mhs['nama'],
                        'jurusan' => $mhs['jurusan'],
                        'net_flow' => $mhs['net_flow'],
                        'nama_lokasi' => $lokasi['nama_lokasi'],
                        'nim' => $mhs['nim'],
                    ];
                }
            }

            return $this->sendResponse($flattened, 'Get data success');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), [], 400);
        }
    }

    public function export()
    {
        $angkatan = Angkatan::where('status', 'Aktiv')->first();
        $result = (new PrometheeService())->proses(); // hasil dari proses()
        $dataLokasi = $result;

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_FOLIO);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');

        $sheet->setCellValue('A1', 'PENEMPATAN KKN')->mergeCells('A1:F1');
        $sheet->setCellValue('A2', 'ANGKATAN ' . $angkatan->angkatan)->mergeCells('A2:F2');
        $sheet->getStyle('A1:A2')->getFont()->setBold(true);
        $sheet->getStyle('A1:F2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $row = 4;

        foreach ($dataLokasi as $lokasi) {
            // Hitung jumlah jurusan unik dan total mahasiswa
            $jurusanSet = collect($lokasi['mahasiswa'])->pluck('jurusan')->unique();
            $fakultasSet = collect($lokasi['mahasiswa'])->pluck('fakultas')->unique();
            $jumlahJurusan = $jurusanSet->count();
            $jumlahFakultas = $fakultasSet->count();
            $jumlahMahasiswa = count($lokasi['mahasiswa']);

            // Baris heading lokasi + jumlah jurusan dan mahasiswa
            $heading = 'Lokasi KKN: ' . $lokasi['nama_lokasi'] . " ({$jumlahFakultas} Fakultas, {$jumlahJurusan} Jurusan, {$jumlahMahasiswa} Mahasiswa)";
            $sheet->setCellValue('A' . $row, $heading);
            $sheet->mergeCells("A{$row}:F{$row}");
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $row++;

            // Header kolom
            $sheet->setCellValue('A' . $row, 'No');
            $sheet->setCellValue('B' . $row, 'Nama Mahasiswa');
            $sheet->setCellValue('C' . $row, 'NIM');
            $sheet->setCellValue('D' . $row, 'Jurusan');
            $sheet->setCellValue('E' . $row, 'Net Flow');
            $sheet->setCellValue('F' . $row, 'Lokasi');

            // Style header
            $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'acb9ca'],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'font' => ['bold' => true]
            ]);

            $row++;

            foreach ($lokasi['mahasiswa'] as $index => $mhs) {
                $sheet->setCellValue('A' . $row, $index + 1);
                $sheet->setCellValue('B' . $row, $mhs['nama']);
                $sheet->setCellValue('C' . $row, $mhs['nim'] ?? '-');
                $sheet->setCellValue('D' . $row, $mhs['jurusan']);
                $sheet->setCellValue('E' . $row, round($mhs['net_flow'], 4));
                $sheet->setCellValue('F' . $row, $lokasi['nama_lokasi']);

                $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $row++;
            }

            $row++; // Spasi antar lokasi
        }

        // Auto size kolom
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $excelFileName = 'PENEMPATAN_KKN_ANGKATAN_' . $angkatan->angkatan . '.xlsx';
        $excelFilePath = public_path($excelFileName);

        $writer = new Xlsx($spreadsheet);
        $writer->save($excelFilePath);

        return response()->download($excelFilePath);
    }
}
