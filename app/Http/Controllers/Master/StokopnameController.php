<?php

namespace App\Http\Controllers\Master;

use App\Models\Stok;
use App\Models\Stokopname;
use App\Models\Storing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StokopnameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $po=Stokopname::with(
            [
                'detail',
                'lokasi'
            ]
        );

        if($request->has('q')){
            $po=$po->where('no_so','like','%'.request('q').'%');
        }

        $po=$po->paginate(25);

        return $po;
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
        $rules=[
            'kode'=>'required',
            'tanggal'=>'required',
            'lokasi'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $s=new Stokopname;
            $s->no_so=$request->input('kode');
            $s->tanggal=date('Y-m-d',strtotime($request->input('tanggal')));
            $s->lokasi_id=$request->input('lokasi');
            $simpan=$s->save();

            if($simpan){
                if($request->has('listBarang')){
                    $listbarang=request('listBarang');
                    $prog=$request->input('prog');

                    foreach($listbarang as $key=>$val){
                        if($val['sto'] > $val['stok']){
                            $s=new Storing;
                            $s->no_storing=$this->autonumber_storing();
                            // $s->no_ref=request('no_ref');
                            $s->tgl=date('Y-m-d',strtotime(request('tanggal')));
                            // $s->no_surat_jalan=request('no_surat_jalan');
                            $s->lokasi_id=request('lokasi');
                            $simpanstoring=$s->save();

                            if($simpanstoring){
                                $brg=\App\Models\Barang::find($val['kd_barang']);
                                $sisa=$val['sto']-$val['stok'];
                                $dos=round($sisa / $brg->pcs);
                                $pcs=$sisa % $brg->pcs;

                                \DB::table('rstoring')
                                    ->insert(
                                        [
                                            'no_storing'=>$s->no_storing,
                                            'kd_brg'=>$val['kd_barang'],
                                            'rak_id'=>$val['rak'],
                                            'dos'=>$dos,
                                            'pcs'=>$pcs
                                        ]
                                    );

                                \DB::table('stok')
                                    ->insert(
                                        [
                                            'kd_brg'=>$val['kd_barang'],
                                            'lokasi_id'=>request('lokasi'),
                                            'rak_id'=>$val['rak'],
                                            'pcs'=>$sisa,
                                            'tgl'=>date('Y-m-d'),
                                            'created_at'=>date('Y-m-d H:i:s'),
                                            'updated_at'=>date('Y-m-d H:i:s')
                                        ]
                                    );
                            }
                        }else if($val['sto'] < $val['stok']){
                            $kurangnya = $val['stok'] - $val['sto'];

                            $pilihstok=\DB::Table('stok')  
                                ->where('kd_brg',$val['kd_barang'])
                                ->where('lokasi_id',request('lokasi'))
                                ->where('pcs','>',0)
                                ->get();

                            foreach($pilihstok as $key2=>$as){
                                //cek dulu kurangnya masih ada atau ngga
                                if($kurangnya > 0){
                                    //cek hasil pengurangannya dari database
                                    //jika dikuranginya masih lebih dari 0, 
                                    // maka update stok nya jadi 0, jika tidak update stok nya adalah jumlah stok - kurangnya
                                    $dikuranginya=$kurangnya - $as->pcs;
                                    if($dikuranginya > 0){
                                        \DB::table('stok')
                                            ->where('id',$as->id)
                                            ->update(
                                                [
                                                    'pcs'=>0
                                                ]
                                            );
                                    }else{
                                        \DB::statement("UPDATE stok SET pcs = pcs-".$kurangnya."
                                            where id='".$as->id."'");
                                    }
                                }

                                $kurangnya=$kurangnya - $as->pcs;
                            }
                        }else{
                            // \DB::Table('stok')
                            //     ->where('kd_brg',$val['kd_barang'])
                            //     ->where('rak_id',$val['rak'])
                            //     ->where('lokasi_id',request('lokasi'))
                            //     ->update(
                            //         [
                            //             'pcs'=>$val['sto']
                            //         ]
                            //     );
                        }

                        \DB::table('rstok_opname')
                            ->insert(
                                [
                                    'no_so'=>request('kode'),
                                    'kd_brg'=>$val['kd_barang'],
                                    'rak_id'=>$val['rak'],
                                    'qty_so'=>$val['stok'],
                                    'qty_prog'=>$val['sto']
                                ]
                            );
                    }
                }

                $data=array(
                    'success'=>true,
                    'pesan'=>'Data berhasil disimpan',
                    'errors'=>''
                );
            }else{
                $data=array(
                    'success'=>false,
                    'pesan'=>'Data gagal disimpan',
                    'errors'=>''
                );
            }

            //hapus dulu stok yang pcs nya 0
            \DB::Table('stok')  
                ->where('pcs',0)
                ->delete();
        }

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Master\Stok  $stok
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $po=Stokopname::with(
            [
                'detail',
                'lokasi'
            ]
        )->find($id);

        return $po;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Stok  $stok
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Master\Stok  $stok
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cus=Stokopname::find($id);

        $hapus=$cus->delete();

        if($hapus){
            \DB::table('rstok_opname')
                ->where('no_so',$id)
                ->delete();


            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil dihapus',
                'errors'=>array()
            );
        }else{
            $data=array(
                'success'=>false,
                'pesan'=>'Data gagal dihapus',
                'errors'=>array()
            );
        }

        return $data;
    }

    public function autonumber_stok_opname()
    {
        $sql=Stokopname::select(\DB::Raw("max(no_so) as maxKode"))
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 10,10);
        $noUrut++;
        $char = "SO-TLG-".date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }

    public function stok_opname_by_lokasi(Request $request,$id)
    {
        $lis=\DB::select("SELECT a.kd_brg,b.nm, b.jual AS harga, a.rak_id, SUM(a.pcs) AS qty_sto FROM stok a
            LEFT JOIN brg b ON b.kd=a.kd_brg
            WHERE a.lokasi_id=$id
            GROUP BY a.rak_id, a.kd_brg");

        return $lis;
    }

    public function autonumber_storing()
    {
        $sql=Storing::select(\DB::Raw("max(no_storing) as maxKode"))
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 11,11);
        $noUrut++;
        $char = "STR-TLG-".date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }
}
