<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Storing extends Model
{
    use SoftDeletes;

    protected $table="storing";
    // protected $primaryKey="kd_bank";

    // protected $dates=['deleted_at'];

}
