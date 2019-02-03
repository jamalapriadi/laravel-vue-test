<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suplier extends Model
{
    use SoftDeletes;

    protected $table="suplier";
    protected $primaryKey="kd";

    // protected $dates=['deleted_at'];

    public function kota()
    {
        return $this->belongsTo('App\Models\Kota','kota_id','kd_kota')
            ->select(
                [
                    'kd_kota',
                    'nm',
                    'jenis'
                ]
            );
    }

}
