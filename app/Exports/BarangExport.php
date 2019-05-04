<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BarangExport implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Barang::limit(5)->get();
    }

    public function map($barang): array
    {
        return [
            $barang->kd,
            $barang->nm,
            $barang->kelompok_id,
            $barang->merk_id,
            $barang->pcs,
            $barang->hrgp,
            $barang->jual
        ];
    }

    public function headings(): array
    {
        return [
            'kd_barang',
            'nm_barang',
            'kelompok_id',
            'merk_id',
            'pcs',
            'harga_pokok',
            'jual'
        ];
    }
}
