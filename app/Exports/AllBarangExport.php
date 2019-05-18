<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AllBarangExport implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Barang::all();
    }

    public function map($barang): array
    {
        return [
            $barang->kd,
            $barang->nm,
            $barang->kelompok_id,
            $barang->merk_id,
            $barang->status,
            $barang->satuan,
            $barang->pcs,
            $barang->hrgb,
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
            'status',
            'satuan',
            'pcs',
            'harga_beli',
            'harga_pokok',
            'jual'
        ];
    }
}
