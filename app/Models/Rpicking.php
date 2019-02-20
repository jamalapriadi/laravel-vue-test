<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Rpicking extends Model
{
    // use SoftDeletes;

    protected $table="rpicking";
    // protected $primaryKey="kd_bank";

    protected $dates=['deleted_at'];

}
