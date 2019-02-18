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

    protected $fillable = [
        'kd', 
        'nm',
        'kelompok_id',
        'merk_id',
        'status',
        'satuan',
        'pcs',
        'hrgb',
        'hrgp',
        'jual'
    ];

    public function kelompok()
    {
        return $this->belongsTo('App\Models\Kelompok','kelompok_id');
    }

    public function merk()
    {
        return $this->belongsTo('App\Models\Merk','merk_id');
    }

    public function stok(){
        return $this->hasMany('App\Models\Stok','kd_brg','kd')
            ->where('stok.pcs','>',0)
            ->orderBy('tgl');
    }

}
