<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $table="order";
    // protected $primaryKey="kd_bank";

    // protected $dates=['deleted_at'];

}
