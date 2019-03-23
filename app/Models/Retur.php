<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Retur extends Model
{
    protected $table="retur";
    protected $primaryKey="no_retur";
    public $incrementing=false;

    public function customer(){
        return $this->belongsTo('App\Models\Customer','customer_id','kd');
    }

    public function order(){
        return $this->belongsTo('App\Models\Order','no_order');
    }

    public function lokasi(){
        return $this->belongsTo('App\Models\Lokasi','lokasi_id');
    }
}