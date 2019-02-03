<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rak extends Model
{
    use SoftDeletes;

    protected $table="rak";
    protected $primaryKey="kd";

    // protected $dates=['deleted_at'];

}
