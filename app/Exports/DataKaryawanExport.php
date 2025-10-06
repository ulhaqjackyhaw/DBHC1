<?php

namespace App\Exports;

use App\Models\DataKaryawan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DataKaryawanExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DataKaryawan::orderBy('nama')
            ->orderBy('lokasi')
            ->orderBy('unit')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'NIK',
            'Nama',
            'GENDER',
            'KODE JABATAN',
            'LOKASI',
            'UNIT',
            'JABATAN',
            'KELOMPOK KELAS JABATAN',
            'GRADE',
            'STATUS KEPEGAWAIAN',
            'ASAL INSTANSI',
            'TANGGAL LAHIR',
            'USIA (TAHUN)',
            'PENDIDIKAN TERAKHIR',
            'TMT',
            'MASA KERJA (TAHUN)',
            'SISA FORMASI',
        ];
    }

    /**
     * @param mixed $dataKaryawan
     * @return array
     */
    public function map($dataKaryawan): array
    {
        // Hitung sisa formasi (vacancy) berdasarkan kode_jabatan, lokasi, unit
        $formasi = \App\Models\Formasi::where('kode_jabatan', $dataKaryawan->kode_jabatan)
            ->where('lokasi', $dataKaryawan->lokasi)
            ->where('unit', $dataKaryawan->unit)
            ->first();
        $kuota = $formasi ? $formasi->kuota : 1;
        $terisi = \App\Models\DataKaryawan::where('kode_jabatan', $dataKaryawan->kode_jabatan)
            ->where('lokasi', $dataKaryawan->lokasi)
            ->where('unit', $dataKaryawan->unit)
            ->count();
        $sisaFormasi = $kuota - $terisi;
        return [
            $dataKaryawan->nik,
            $dataKaryawan->nama,
            $dataKaryawan->gender,
            $dataKaryawan->kode_jabatan,
            $dataKaryawan->lokasi,
            $dataKaryawan->unit,
            $dataKaryawan->jabatan,
            $dataKaryawan->kelompok_kelas_jabatan,
            $dataKaryawan->grade,
            $dataKaryawan->status_kepegawaian,
            $dataKaryawan->asal_instansi,
            $dataKaryawan->tanggal_lahir,
            $this->calculateAge($dataKaryawan->tanggal_lahir),
            $dataKaryawan->pendidikan_terakhir,
            $dataKaryawan->tmt,
            $this->calculateWorkPeriod($dataKaryawan->tmt),
            $sisaFormasi,
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

    /**
     * Calculate age from birth date
     */
    private function calculateAge($birthDate)
    {
        if (!$birthDate)
            return 0;

        // Parse berbagai format tanggal (dd/mm/yyyy, dd-mm-yyyy, yyyy-mm-dd)
        $parsedDate = null;
        if (strpos($birthDate, '/') !== false) {
            $parts = explode('/', $birthDate);
            if (count($parts) === 3) {
                $parsedDate = \DateTime::createFromFormat('d/m/Y', $birthDate);
            }
        } elseif (strpos($birthDate, '-') !== false) {
            $parts = explode('-', $birthDate);
            if (count($parts) === 3) {
                if (strlen($parts[0]) === 4) {
                    $parsedDate = \DateTime::createFromFormat('Y-m-d', $birthDate);
                } else {
                    $parsedDate = \DateTime::createFromFormat('d-m-Y', $birthDate);
                }
            }
        }

        if (!$parsedDate)
            return 0;

        $today = new \DateTime();
        $age = $today->diff($parsedDate)->y;

        return $age;
    }

    /**
     * Calculate work period from TMT date
     */
    private function calculateWorkPeriod($tmtDate)
    {
        if (!$tmtDate)
            return 0;

        // Parse berbagai format tanggal (dd/mm/yyyy, dd-mm-yyyy, yyyy-mm-dd)
        $parsedDate = null;
        if (strpos($tmtDate, '/') !== false) {
            $parts = explode('/', $tmtDate);
            if (count($parts) === 3) {
                $parsedDate = \DateTime::createFromFormat('d/m/Y', $tmtDate);
            }
        } elseif (strpos($tmtDate, '-') !== false) {
            $parts = explode('-', $tmtDate);
            if (count($parts) === 3) {
                if (strlen($parts[0]) === 4) {
                    $parsedDate = \DateTime::createFromFormat('Y-m-d', $tmtDate);
                } else {
                    $parsedDate = \DateTime::createFromFormat('d-m-Y', $tmtDate);
                }
            }
        }

        if (!$parsedDate)
            return 0;

        $today = new \DateTime();
        $workYears = $today->diff($parsedDate)->y;

        return $workYears;
    }
}