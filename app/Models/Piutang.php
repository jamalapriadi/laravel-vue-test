<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Piutang extends Model
{
    protected $table="piutang";
    protected $primaryKey="no_piutang";
    public $incrementing=false;

    public function orders(){
        return $this->belongsTo('App\Models\Order','no_order');
    }
}