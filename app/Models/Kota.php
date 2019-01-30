<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kota extends Model
{
    use SoftDeletes;

    protected $table="kota";
    protected $primaryKey="nm";

    protected $dates=['deleted_at'];

}
