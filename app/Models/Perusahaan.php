<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Perusahaan extends Model
{
    // use SoftDeletes;

    protected $table="perusahaan";

    // protected $dates=['deleted_at'];

    public function ket(){
        return $this->hasOne('App\Models\Ket','perusahaan_id','id');
    }

}
