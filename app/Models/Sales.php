<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use SoftDeletes;

    protected $table="sales";
    // protected $primaryKey="kd_bank";

    // protected $dates=['deleted_at'];

}
