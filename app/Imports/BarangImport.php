<?php

namespace App\Imports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class BarangImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Barang([
            'kd' => $row['kd_barang'],
            'nm' => $row['nm_barang'],
            'kelompok_id' => $row['kelompok_id'],
            'merk_id' => $row['merk_id'],
            // 'status' => $row['status'],
            // 'satuan' => $row['satuan'],
            'pcs' => $row['pcs'],
            // 'hrgb' => $row['harga_beli'],
            'hrgp' => $row['harga_pokok'],
            'jual' => $row['jual']
        ]);
    }
}
