<?php

namespace App\Filament\Exports;

use App\Models\Kantor;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class AdvancedKantorExporter extends Exporter
{
    protected static ?string $model = Kantor::class;

    public static function getColumns(): array
    {
        // This exporter uses a custom Excel generation method
        return [];
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return $query->with(['sufixes.subSufixes', 'totals']);
    }

    public static function createExcelFile(): string
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Kantor Detail');

        // Get data
        $query = static::modifyQuery(Kantor::query());
        $records = $query->get();
        $sufixNames = \App\Models\Sufix::pluck('nama_sufix')->unique()->sort()->values();

        // Setup styles
        $headerStyle = [
            'font' => ['bold' => true, 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E6E6FA']]
        ];

        $subHeaderStyle = [
            'font' => ['bold' => true, 'size' => 9],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0F8FF']]
        ];

        $dataStyle = [
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER]
        ];

        // Calculate column positions
        $col = 1;
        $basicCols = 6; // NO, KANTOR, NOPEN, KAB/KOTA, ALOKASI KPM, ALOKASI JUMLAH UANG
        $sufixCols = count($sufixNames) * 7; // Each sufix has 7 columns
        $totalCols = 5; // JUMLAH columns

        // Three-row header structure as per HTML example
        
        // Row 1: Top level headers with rowspan and colspan
        $currentCol = 1;
        
        // Basic columns with rowspan=3
        $basicHeaders = ['NO', 'KANTOR', 'NOPEN', 'KAB/KOTA', 'ALOKASI KPM', 'ALOKASI JUMLAH UANG'];
        foreach ($basicHeaders as $header) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($currentCol);
            $sheet->setCellValue("{$colLetter}1", $header);
            $sheet->mergeCells("{$colLetter}1:{$colLetter}3"); // rowspan=3
            $sheet->getStyle("{$colLetter}1:{$colLetter}3")->applyFromArray($headerStyle);
            $currentCol++;
        }
        
        // SUFIX header (colspan = 7 * number of sufixes)
        $sufixTotalCols = count($sufixNames) * 7;
        $sufixStartCol = $currentCol;
        $sufixEndCol = $sufixStartCol + $sufixTotalCols - 1;
        $sufixStartLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($sufixStartCol);
        $sufixEndLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($sufixEndCol);
        
        $sheet->setCellValue("{$sufixStartLetter}1", 'SUFIX');
        $sheet->mergeCells("{$sufixStartLetter}1:{$sufixEndLetter}1");
        $sheet->getStyle("{$sufixStartLetter}1:{$sufixEndLetter}1")->applyFromArray($headerStyle);
        
        // TOTAL header (colspan = 4)
        $totalStartCol = $sufixEndCol + 1;
        $totalEndCol = $totalStartCol + 3; // 4 columns: JUMLAH ALOKASI BNBA, JUMLAH ALOKASI BIAYA, JUMLAH REALISASI, JUMLAH REALISASI BIAYA
        $totalStartLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalStartCol);
        $totalEndLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($totalEndCol);
        
        $sheet->setCellValue("{$totalStartLetter}1", 'TOTAL');
        $sheet->mergeCells("{$totalStartLetter}1:{$totalEndLetter}1");
        $sheet->getStyle("{$totalStartLetter}1:{$totalEndLetter}1")->applyFromArray($headerStyle);
        
        // PROSENTASE header (rowspan=3)
        $prosentaseCol = $totalEndCol + 1;
        $prosentaseLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($prosentaseCol);
        $sheet->setCellValue("{$prosentaseLetter}1", 'PROSENTASE');
        $sheet->mergeCells("{$prosentaseLetter}1:{$prosentaseLetter}3");
        $sheet->getStyle("{$prosentaseLetter}1:{$prosentaseLetter}3")->applyFromArray($headerStyle);
        
        // Row 2: Individual SUFIX groups and TOTAL sub-headers
        $currentCol = $sufixStartCol;
        
        // Each SUFIX group (colspan=7)
        foreach ($sufixNames as $sufixName) {
            $sufixLabel = "SUFIX - " . strtoupper($sufixName);
            $groupStartCol = $currentCol;
            $groupEndCol = $currentCol + 6; // 7 columns per sufix
            $groupStartLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($groupStartCol);
            $groupEndLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($groupEndCol);
            
            $sheet->setCellValue("{$groupStartLetter}2", $sufixLabel);
            $sheet->mergeCells("{$groupStartLetter}2:{$groupEndLetter}2");
            $sheet->getStyle("{$groupStartLetter}2:{$groupEndLetter}2")->applyFromArray($subHeaderStyle);
            
            $currentCol = $groupEndCol + 1;
        }
        
        // TOTAL sub-headers (rowspan=2)
        $totalSubHeaders = ['JUMLAH ALOKASI BNBA', 'JUMLAH ALOKASI BIAYA', 'JUMLAH REALISASI', 'JUMLAH REALISASI BIAYA'];
        $currentCol = $totalStartCol;
        foreach ($totalSubHeaders as $header) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($currentCol);
            $sheet->setCellValue("{$colLetter}2", $header);
            $sheet->mergeCells("{$colLetter}2:{$colLetter}3"); // rowspan=2
            $sheet->getStyle("{$colLetter}2:{$colLetter}3")->applyFromArray($subHeaderStyle);
            $currentCol++;
        }
        
        // Row 3: Individual column headers for each SUFIX group
        $currentCol = $sufixStartCol;
        
        foreach ($sufixNames as $sufixName) {
            $sufixDetailHeaders = ['ALOKASI', 'ALOKASI BIAYA', 'REALISASI', 'REALISASI BIAYA', 'GAGAL BAYAR/TOLAK', 'SISA AKTIF', 'SISA BIAYA'];
            
            foreach ($sufixDetailHeaders as $header) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($currentCol);
                $sheet->setCellValue("{$colLetter}3", $header);
                $sheet->getStyle("{$colLetter}3")->applyFromArray($subHeaderStyle);
                $currentCol++;
            }
        }

        // Data rows start from row 4
        $row = 4;
        $counter = 0;
        
        foreach ($records as $record) {
            $counter++;
            
            // Basic data (columns 1-6)
            $sheet->setCellValue('A' . $row, $counter);
            $sheet->setCellValue('B' . $row, $record->kantor);
            $sheet->setCellValue('C' . $row, $record->nopen);
            $sheet->setCellValue('D' . $row, $record->kab_kota ?? '');
            $sheet->setCellValue('E' . $row, number_format($record->alokasi_kpm, 0, ',', '.'));
            $sheet->setCellValue('F' . $row, number_format($record->alokasi_jml_uang, 0, ',', '.'));
            
            // Sufix data (starting from column 7)
            $currentCol = 7;
            foreach ($sufixNames as $sufixName) {
                $sufix = $record->sufixes->where('nama_sufix', $sufixName)->first();
                
                if ($sufix) {
                    $alokasi = $sufix->subSufixes->sum('alokasi');
                    $alokasibiaya = $sufix->subSufixes->sum('alokasi_biaya');
                    $realisasi = $sufix->subSufixes->sum('realisasi');
                    $realisasibiaya = $sufix->subSufixes->sum('realisasi_biaya');
                    $gagalbayar = $sufix->subSufixes->sum('gagal_bayar_tolak');
                    $sisaaktif = $sufix->subSufixes->sum('sisa_aktif');
                    $sisabiaya = $sufix->subSufixes->sum('sisa_biaya');
                    
                    $values = [
                        $alokasi > 0 ? number_format($alokasi, 0, ',', '.') : '-',
                        $alokasibiaya > 0 ? number_format($alokasibiaya, 0, ',', '.') : '-',
                        $realisasi > 0 ? number_format($realisasi, 0, ',', '.') : '-',
                        $realisasibiaya > 0 ? number_format($realisasibiaya, 0, ',', '.') : '-',
                        $gagalbayar > 0 ? number_format($gagalbayar, 0, ',', '.') : '-',
                        $sisaaktif > 0 ? number_format($sisaaktif, 0, ',', '.') : '-',
                        $sisabiaya > 0 ? number_format($sisabiaya, 0, ',', '.') : '-'
                    ];
                } else {
                    $values = ['-', '-', '-', '-', '-', '-', '-'];
                }
                
                foreach ($values as $value) {
                    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($currentCol);
                    $sheet->setCellValue($colLetter . $row, $value);
                    $currentCol++;
                }
            }
            
            // Total data (4 columns)
            $totalAlokasiBnba = $record->totals->sum('jumlah_alokasi_bnba');
            $totalAlokasiBiaya = $record->totals->sum('jumlah_alokasi_biaya');
            $totalRealisasi = $record->totals->sum('jumlah_realisasi');
            $totalRealisasiBiaya = $record->totals->sum('jumlah_realisasi_biaya');
            
            $totalValues = [
                $totalAlokasiBnba > 0 ? number_format($totalAlokasiBnba, 0, ',', '.') : '-',
                $totalAlokasiBiaya > 0 ? number_format($totalAlokasiBiaya, 0, ',', '.') : '-',
                $totalRealisasi > 0 ? number_format($totalRealisasi, 0, ',', '.') : '-',
                $totalRealisasiBiaya > 0 ? number_format($totalRealisasiBiaya, 0, ',', '.') : '-'
            ];
            
            foreach ($totalValues as $value) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($currentCol);
                $sheet->setCellValue($colLetter . $row, $value);
                $currentCol++;
            }
            
            // Prosentase column (last column)
            $avgPercentage = $record->totals->avg('persentase');
            $prosentaseValue = $avgPercentage ? number_format($avgPercentage, 2) . '%' : '-';
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($currentCol);
            $sheet->setCellValue($colLetter . $row, $prosentaseValue);
            
            $row++;
        }

        // Apply data styles (starting from row 4)
        $lastRow = $row - 1;
        $lastCol = $currentCol;
        $lastColLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastCol);
        $sheet->getStyle("A4:{$lastColLetter}{$lastRow}")->applyFromArray($dataStyle);

        // Auto-size columns
        for ($i = 1; $i <= $lastCol; $i++) {
            $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
            $sheet->getColumnDimension($colLetter)->setAutoSize(true);
        }

        // Set row heights for 3-row header structure
        $sheet->getRowDimension(1)->setRowHeight(25);
        $sheet->getRowDimension(2)->setRowHeight(25);
        $sheet->getRowDimension(3)->setRowHeight(25);

        // Save to temp file
        $filename = 'laporan_kantor_detail_' . date('Y-m-d_H-i-s') . '.xlsx';
        $filepath = storage_path('app/exports/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filepath);

        return $filepath;
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'Your advanced kantor export with multi-level headers has been completed successfully.';
    }
}
