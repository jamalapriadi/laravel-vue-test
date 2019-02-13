<?php

namespace App\Imports;

use App\Models\Rak;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RakImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Rak([
            'kd' => $row['kd_rak'],
            'nm' => $row['nm_rak'],
            'lokasi_id' => $row['lokasi_id']
        ]);
    }
}
