<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prgrm extends Model
{
    use SoftDeletes;

    protected $table="prgrm";
    // protected $primaryKey="kd_bank";

    // protected $dates=['deleted_at'];

}
