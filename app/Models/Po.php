<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Po extends Model
{
    // use SoftDeletes;

    protected $table="po";
    protected $primaryKey="no_po";
    public $incrementing=false;

    // protected $dates=['deleted_at'];

    public function detail(){
        return $this->belongsToMany('App\Models\Barang','rpo','no_po','kd_brg','no_po','kd')
            ->withPivot(
                [
                    'no_po',
                    'kd_brg',
                    'dos',
                    'pcs',
                    'total_pcs',
                    'lokasi_id',
                    'jumlah'
                ]
            );
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer','customer_id','kd');
    }

    public function perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan','perusahaan_id');
    }

    public function lokasi(){
        return $this->belongsTo('App\Models\Lokasi','lokasi_id');
    }

}
