<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Mutasi extends Model
{
    // use SoftDeletes;

    protected $table="mutasi";
    protected $primaryKey="no_mutasi";
    public $incrementing=false;

    // protected $dates=['deleted_at'];

    public function gudang_lama(){
        return $this->belongsTo('App\Models\Lokasi','lokasil');
    }

    public function gudang_baru(){
        return $this->belongsTo('App\Models\Lokasi','lokasib');
    }

    public function perusahaan(){
        return $this->belongTo('App\Models\Perusahaan','perusahaan_id');
    }

    public function detail(){
        return $this->belongsToMany('App\Models\Barang','rmutasi','no_mutasi','kd_brg','no_mutasi','kd')
            ->select(
                [
                    'b.nm as rak_lama',
                    'c.nm as rak_baru',
                    'brg.kd',
                    'brg.nm',
                    'brg.jual'
                ]
            )
            ->withPivot(
                [
                    'no_mutasi',
                    'kd_brg',
                    'rakl',
                    'rakb',
                    'dos',
                    'pcs'
                ]
            )->leftJoin('rak as b','b.kd','=','rmutasi.rakl')
            ->leftJoin('rak as c','c.kd','=','rmutasi.rakb');
    }

}
