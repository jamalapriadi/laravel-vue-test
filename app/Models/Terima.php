<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Terima extends Model
{
    use SoftDeletes;

    protected $table="terima";
    // protected $primaryKey="kd_bank";

    // protected $dates=['deleted_at'];

}
