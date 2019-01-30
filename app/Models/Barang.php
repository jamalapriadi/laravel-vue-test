<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use SoftDeletes;

    protected $table="brg";
    protected $primaryKey="kd_bank";

    protected $dates=['deleted_at'];

}
