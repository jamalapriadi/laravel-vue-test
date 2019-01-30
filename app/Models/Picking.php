<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Picking extends Model
{
    use SoftDeletes;

    protected $table="picking";
    // protected $primaryKey="kd_bank";

    // protected $dates=['deleted_at'];

}
