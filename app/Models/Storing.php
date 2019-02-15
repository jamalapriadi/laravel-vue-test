<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Storing extends Model
{
    use SoftDeletes;

    protected $table="storing";
    // protected $primaryKey="kd_bank";

    // protected $dates=['deleted_at'];

    public function lokasi(){
        return $this->belongsTo('App\Models\Lokasi','lokasi_id');
    }

    public function detail(){
        return $this->belongsToMany('App\Models\Barang','rstoring','no_storing','kd_brg','no_storing','kd')
            ->withPivot(
                [
                    'no_storing',
                    'kd_brg',
                    'rak_id',
                    'dos',
                    'pcs'
                ]
            );
    }

}
