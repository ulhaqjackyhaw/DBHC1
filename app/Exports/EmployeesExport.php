<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeesExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Ambil semua data karyawan tanpa pagination
        return Employee::all();
    }

    /**
     * Menentukan header untuk file Excel.
     */
    public function headings(): array
    {
        // Sesuaikan dengan nama kolom di tabel employees Anda
        return [
            'ID',
            'NIK',
            'Nama',
            'GENDER',
            'UNIT',
            'JABATAN',
            'KELOMPOK_KELAS_JABATAN',
            'GRADE',
            'STATUS_KEPEGAWAIAN',
            'USIA',
            'PENDIDIKAN',
            'MASA_KERJA',
            'Created At',
            'Updated At',
        ];
    }
}