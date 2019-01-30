<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Po extends Model
{
    use SoftDeletes;

    protected $table="po";
    // protected $primaryKey="kd_bank";

    // protected $dates=['deleted_at'];

}
