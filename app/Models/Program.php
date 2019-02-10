<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    // use SoftDeletes;

    protected $table="prgrm";
    protected $primaryKey="nmr";
    public $incrementing=false;

    // protected $dates=['deleted_at'];

    public function detail(){
        return $this->belongsToMany('App\Models\Barang','rprogram','nmr_program','kd_brg','nmr','kd')
            ->withPivot(
                [
                    'kd_brg',
                    'nmr_program',
                    'qty',
                    'point'
                ]
            );
    }

}
