<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Jeniscustomer extends Model
{
    // use SoftDeletes;

    protected $table="jenis_customer";
    protected $primaryKey="jns_customer";

    // protected $dates=['deleted_at'];
    public $timestamps=false;
    public $incrementing=false;

}
