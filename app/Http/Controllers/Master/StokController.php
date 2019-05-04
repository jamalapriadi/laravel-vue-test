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

        // SELECT a.kd, a.nm as nama_barang, 
        // b.pcs AS jumlah_stok,
        // d.nm AS nama_lokasi, 
        // c.nm AS nama_rak,
        // a.jual AS harga
        // FROM brg a
        // LEFT JOIN stok b ON b.kd_brg=a.kd
        // LEFT JOIN rak c ON c.kd=b.rak_id
        // LEFT JOIN lokasi d ON d.id=b.lokasi_id
        // WHERE b.pcs > 0

        $lis=\App\Models\Barang::leftJoin('stok as b','b.kd_brg','=','brg.kd')
            ->leftJoin('rak as c','c.kd','=','b.rak_id')
            ->leftJoin('lokasi as d','d.id','=','b.lokasi_id')
            ->select(
                'brg.kd',
                'brg.nm as nama_barang',
                \DB::Raw("sum(b.pcs) as jumlah_stok"),
                'd.nm as nama_lokasi',
                'c.nm as nama_rak',
                'brg.jual as harga'
            )->where('b.pcs','>',0);

        if($request->has('lokasi') && $request->input('lokasi')!=null){
            // $wl.=" AND ax.lokasi_id=".$request->input('lokasi');
            $lis=$lis->where('b.lokasi_id',$request->input('lokasi'));
        }

        if($request->has('rak') && $request->input('rak')!=null){
            // $wl.=" AND ax.rak_id=".$request->input('rak');
            $lis=$lis->where('b.rak_id',$request->input('rak'));
        }

        if($request->has('q') && $request->input('q')!=null){
            $lis=$lis->where('brg.nm','like','%'.$request->input('q').'%');
        }

        // GROUP BY a.kd, b.lokasi_id, b.rak_id
        $lis=$lis->groupBy('brg.kd','b.lokasi_id','b.rak_id')->paginate(25);


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
