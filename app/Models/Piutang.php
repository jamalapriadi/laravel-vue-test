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

    public function customer(){
        return $this->belongsTo('App\Models\Customer','customer_id','kd')
            ->select(
                [
                    'kd',
                    'jenis_customer',
                    'nm',
                    'nm_toko'
                ]
            );
    }

    public function detail(){
        return $this->belongsToMany('App\Models\Order','rpiutang','no_piutang','no_order')
            ->withPivot(
                [
                    'no_piutang',
                    'jns_pembayaran',
                    'bank',
                    'no_cek_bg',
                    'no_order',
                    'tgl_jt_transfer',
                    'nominal',
                    'keterangan'
                ]
            );
    }
}