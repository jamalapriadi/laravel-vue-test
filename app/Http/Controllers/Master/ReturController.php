<?php

namespace App\Http\Controllers\Master;

use App\Models\Retur;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReturController extends Controller
{
    public function index(Request $request){
        
    }

    public function store(Request $request){

    }

    public function show(Request $request,$id){

    }

    public function update(Request $request,$id){

    }

    public function destroy(Request $request,$id){

    }

    public function autonumber_retur(Request $request){
        $sql=Retur::select(\DB::Raw("max(no_retur) as maxKode"))
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 11,11);
        $noUrut++;
        $char = "RTR-TLG-".date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }
}