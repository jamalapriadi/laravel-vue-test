<?php

namespace App\Http\Controllers\Master;

use App\Models\Stok;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StokController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Master\Stok  $stok
     * @return \Illuminate\Http\Response
     */
    public function show(Stok $stok)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Stok  $stok
     * @return \Illuminate\Http\Response
     */
    public function edit(Stok $stok)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Master\Stok  $stok
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stok $stok)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Master\Stok  $stok
     * @return \Illuminate\Http\Response
     */
    public function destroy(Stok $stok)
    {
        //
    }

    public function lihat_stok(Request $request)
    {
        $wl="";

        if($request->has('lokasi') && $request->input('lokasi')!=null){
            $wl.=" AND ax.lokasi_id=".$request->input('lokasi');
        }

        if($request->has('rak') && $request->input('rak')!=null){
            $wl.=" AND ax.rak_id=".$request->input('rak');
        }

        $lis=\App\Models\Barang::with(
            [
                'merk',
                'kelompok'
            ]
        )->select(
            'kd','nm','merk_id','kelompok_id',
            \DB::raw("IFNULL((
                SELECT SUM(pcs) AS jumlah_stok FROM stok ax
                WHERE ax.kd_brg=brg.kd
                $wl
                GROUP BY ax.kd_brg
            ),0) AS jumlah_stok")
        )->paginate(25);


        // $lis=\DB::select("SELECT a.kd, a.nm, b.nm AS merk_name,c.nm AS kelompok_name,
        //     IFNULL((
        //         SELECT SUM(pcs) AS jumlah_stok FROM stok ax
        //         WHERE ax.kd_brg=a.kd
        //         $wl
        //         GROUP BY ax.kd_brg
        //     ),0) AS jumlah_stok
        //     FROM brg a
        //     LEFT JOIN merk b ON b.id=a.merk_id
        //     LEFT JOIN klmpk c ON c.id=a.kelompok_id");

        return $lis;
    }
}
