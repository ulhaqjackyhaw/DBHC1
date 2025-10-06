<?php

namespace App\Exports;

use App\Models\Formasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FormasiExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Formasi::orderBy('lokasi')
            ->orderBy('unit')
            ->orderBy('jabatan')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'KODE JABATAN',
            'LOKASI',
            'UNIT',
            'JABATAN',
            'KELOMPOK KELAS JABATAN',
            'GRADE',
            'KUOTA',
        ];
    }

    /**
     * @param mixed $formasi
     * @return array
     */
    public function map($formasi): array
    {
        return [
            $formasi->kode_jabatan,
            $formasi->lokasi,
            $formasi->unit,
            $formasi->jabatan,
            $formasi->kelompok_kelas_jabatan,
            $formasi->grade,
            $formasi->kuota,
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}