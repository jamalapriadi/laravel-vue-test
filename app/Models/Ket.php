<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Ket extends Model
{
    // use SoftDeletes;

    protected $table="ket";
    protected $primaryKey="no_hp";

    public $incrementing=false;
    // protected $dates=['deleted_at'];

}
