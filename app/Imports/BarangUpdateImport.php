<?php

namespace App\Imports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BarangUpdateImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $barang=Barang::find($row['kd_barang']);
        $barang->nm=$row['nm_barang'];
        $barang->kelompok_id=$row['kelompok_id'];
        $barang->merk_id=$row['merk_id'];
        $barang->pcs=$row['pcs'];
        $barang->hrgp=$row['harga_pokok'];
        $barang->jual=$row['jual'];
        $barang->save();

        return;
    }
}
