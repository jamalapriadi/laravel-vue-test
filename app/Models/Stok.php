<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stok extends Model
{
    use SoftDeletes;

    protected $table="stok";
    // protected $primaryKey="kd_bank";

    // protected $dates=['deleted_at'];

}
