<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Suplier extends Model
{
    use SoftDeletes;

    protected $table="suplier";
    // protected $primaryKey="kd_bank";

    // protected $dates=['deleted_at'];

}
