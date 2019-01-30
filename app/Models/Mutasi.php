<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mutasi extends Model
{
    use SoftDeletes;

    protected $table="mutasi";
    // protected $primaryKey="kd_bank";

    // protected $dates=['deleted_at'];

}
