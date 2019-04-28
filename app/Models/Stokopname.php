<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Stokopname extends Model
{
    // use SoftDeletes;

    protected $table="stok_opname";
    protected $primaryKey="no_so";
    public $incrementing=false;

    // protected $dates=['deleted_at'];

    public function detail(){
        return $this->belongsToMany('App\Models\Barang','rstok_opname','no_so','kd_brg','no_so','kd')
            ->withPivot(
                [
                    'no_so',
                    'kd_brg',
                    'rak_id',
                    'qty_so',
                    'qty_prog'
                ]
            );
    }

    public function lokasi(){
        return $this->belongsTo('App\Models\Lokasi','lokasi_id');
    }

}
