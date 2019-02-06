<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $table="customer";
    protected $primaryKey="kd";
    public $incrementing=false;

    protected $dates=['deleted_at'];

    public function kota(){
        return $this->belongsTo('App\Models\Kota','kota_id','kd_kota');
    }

}
