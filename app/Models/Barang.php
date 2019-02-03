<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use SoftDeletes;

    protected $table="brg";
    protected $primaryKey="kd";
    public $incrementing=false;

    protected $dates=['deleted_at'];

    public function kelompok()
    {
        return $this->belongsTo('App\Models\Kelompok','kelompok_id');
    }

    public function merk()
    {
        return $this->belongsTo('App\Models\Merk','merk_id');
    }

}
