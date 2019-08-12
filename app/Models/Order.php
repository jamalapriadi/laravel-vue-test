<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    // use SoftDeletes;

    protected $table="orders";
    protected $primaryKey="no_order";
    public $incrementing=false;

    // protected $dates=['deleted_at'];

    public function detail(){
        return $this->belongsToMany('App\Models\Barang','rorder','no_order','kd_brg','no_order','kd')
            ->withPivot(
                [
                    'no_order',
                    'kd_brg',
                    'dos',
                    'pcs',
                    'hrg',
                    'diskon_rupiah',
                    'diskon_persen',
                    'diskon_persen_2',
                    'subtotal',
                    'jumlah',
                    'status_retur'
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

    public function sales()
    {
        return $this->belongsTo('App\Models\Sales','sales_id');
    }

    public function lokasi()
    {
        return $this->belongsTo('App\Models\Lokasi','lokasi_id');
    }

    public function picking(){
        return $this->belongsTo('App\Models\Picking','kd_picking','kd_picking');
    }

    public function piutang(){
        return $this->belongsToMany('App\Models\Piutang','rpiutang','no_piutang','no_order');
    }

}
