<?php

namespace App\Exports;

use App\Models\Rak;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RakExport implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Rak::limit(5)->get();
    }

    public function map($barang): array
    {
        return [
            $barang->kd,
            $barang->nm,
            $barang->lokasi_id
        ];
    }

    public function headings(): array
    {
        return [
            'kd_rak',
            'nm_rak',
            'lokasi_id'
        ];
    }
}
