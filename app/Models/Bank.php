<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use SoftDeletes;

    protected $table="bank";
    protected $primaryKey="kd_bank";

    protected $dates=['deleted_at'];

}
