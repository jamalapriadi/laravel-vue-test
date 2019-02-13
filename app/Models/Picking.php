<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Picking extends Model
{
    // use SoftDeletes;

    protected $table="picking";
    protected $primaryKey="kd_picking";
    public $incrementing=false;

    // protected $dates=['deleted_at'];

    public function po(){
        return $this->belongsTo('App\Models\Po','no_po','no_po');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer','customer_id','kd');
    }

    public function perusahaan()
    {
        return $this->belongsTo('App\Models\Perusahaan','perusahaan_id');
    }

    public function sales()
    {
        return $this->belongsTo('App\Models\Sales','sales_id');
    }

    public function lokasi()
    {
        return $this->belongsTo('App\Models\Lokasi','lokasi_id');
    }

    public function detail(){
        return $this->belongsToMany('App\Models\Barang','rpicking','kd_picking','kd_brg','kd_picking','kd')
            ->withPivot(
                [
                    'kd_picking',
                    'kd_brg',
                    'pdos',
                    'ppcs',
                    'dos',
                    'pcs'
                ]
            );
    }

}
