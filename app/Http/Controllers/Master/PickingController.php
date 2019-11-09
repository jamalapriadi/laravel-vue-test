<?php

namespace App\Http\Controllers\Master;

use App\Models\Picking;
use App\Models\Po;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PickingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $picking=Picking::with(
            [
                'po',
                'po.customer',
                'po.lokasi',
                'perusahaan'
            ]
        )->where('perusahaan_id',auth()->user()->perusahaan_id);

        $picking=$picking->paginate(25);

        return $picking;
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
            'no_po'=>'required',
            'tanggal'=>'required',
            'kodes'=>'required',
            'dos'=>'required',
            'pcs'=>'required',
            'status_kurang'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all(),
                'adahutang'=>false
            );
        }else{
            
            $kode=$this->autonumber_picking();

            $p=new Picking;
            $p->kd_picking=$kode;
            $p->no_po=request('no_po');
            $p->ket=request('keterangan');
            $p->tgl=date('Y-m-d',strtotime(request('tanggal')));
            $p->perusahaan_id=auth()->user()->perusahaan_id;
            $p->insert_user=auth()->user()->username;
            $p->update_user=auth()->user()->username;
            $p->status_terpenuhi=request('status_kurang');

            $simpan=$p->save();

            if($simpan){
                
                if($request->has('status_kurang')){
                    $statuskurang=$request->input('status_kurang');
                    $tambahan=array();

                    if($statuskurang=="N"){
                        if($request->has('kurang')){
                            $kurang=request('kurang');
        
                            foreach($kurang as $key=>$val){
                                $cekB=\App\Models\Barang::find($val['kd_brg']);

                                $kurang_dos=round((int)$val['kurangnya'] / $cekB->pcs);
                                $kurang_pcs=round((int)$val['kurangnya'] % $cekB->pcs);

                                $tambahan[]=array(
                                    'kd_brg'=>$val['kd_brg'],
                                    'dos'=>$kurang_dos,
                                    'pcs'=>$kurang_pcs,
                                    'total_pcs'=>$val['kurangnya']
                                );
                            }
                        }
                    }


                    if($request->has('tidakdistok')){
                        $tidakdistok=request('tidakdistok');
    
                        if(count($tidakdistok) > 0){
                            foreach($tidakdistok as $key=>$val){
                                $tambahan[]=array(
                                    'kd_brg'=>$val['kd_brg'],
                                    'dos'=>$val['dos'],
                                    'pcs'=>$val['pcs'],
                                    'total_pcs'=>$val['total_pcs']
                                );
                            }   
                        }
                    }

                    if(count($tambahan)>0){
                        $posekarang=Po::find(request('no_po'));
                        $posekarang->no_ref_po=request('no_po');

                        $simpancus=$posekarang->save();

                        if($simpancus){
                            \DB::table('rpo')
                                ->where('no_po',request('no_po'))
                                ->delete();


                            foreach($tambahan as $key=>$val){
                                \DB::table('rpo')
                                    ->insert(
                                        [
                                            'no_po'=>request('no_po'),
                                            'kd_brg'=>$val['kd_brg'],
                                            'dos'=>$val['dos'],
                                            'pcs'=>$val['pcs'],
                                            'total_pcs'=>$val['total_pcs']
                                        ]
                                    );
                            }
                        }
                    }


                }

                if($request->has('kodes')){
                    $kodes=request('kodes');
                    $detail_no_ref_po=array();

                    foreach($kodes as $key=>$val){
                        $cekB=\App\Models\Barang::find($val);
                        $totalpcnya=($cekB->pcs * $request->input('pdos')[$key]) + $request->input('ppcs')[$key];
                        $total_diinput=($cekB->pcs * $request->input('dos')[$key]) + $request->input('pcs')[$key];

                        if($request->input('dos')[$key]!=0){
                            $final_dos=$request->input('pdos')[$key] - $request->input('dos')[$key];   
                        }else{
                            $final_dos=0;    
                        }

                        if($request->input('pcs')[$key]!=0){
                            $final_pcs=$request->input('jumlah')[$key] - $request->input('pcs')[$key];
                        }else{
                            $final_pcs=0;
                        }
                        
                        $final_total_diinput=($cekB->pcs * $final_dos) + $final_pcs;

                        //jika total yang diinput kurang dari totalpc nya,, maka summary kan dengan no refpo sebelumnya jika ada
                        if($total_diinput < $totalpcnya)
                        {
                            //cek apakah dia ada no ref po nya atau tidak
                            $cekPo=\App\Models\Po::where('no_ref_po',request('no_po'))
                                ->get();
                            
                            if(count($cekPo)>0){
                                //cek apakah di rpo ada kode barang ini atau tidak
                                foreach($cekPo as $norefpo){
                                    $cekDetailPo=\DB::table('rpo')
                                        ->where('no_po',$norefpo->no_po)
                                        ->where('kd_brg',$val)
                                        ->get();

                                    if(count($cekDetailPo)>0)
                                    {
                                        \DB::statement("UPDATE rpo SET dos = dos+".$final_dos.",
                                            pcs = pcs+".$final_pcs.",
                                            total_pcs = total_pcs+".$final_total_diinput.",
                                            jumlah = jumlah +".$final_total_diinput." 
                                            where no_po='".$norefpo->no_po."'
                                            AND kd_brg='".$val."'");   
                                    }else{
                                        \DB::table('rpo')
                                            ->insert(
                                                [
                                                    'no_po'=>$norefpo->no_po,
                                                    'kd_brg'=>$val,
                                                    'dos'=>$final_dos,
                                                    'pcs'=>$final_pcs,
                                                    'total_pcs'=>$final_total_diinput,
                                                    'lokasi_id'=>request('lokasi'),
                                                    'jumlah'=>$final_total_diinput
                                                ]
                                            );
                                    }
                                }

                                \DB::table('rpicking')
                                    ->insert(
                                        [
                                            'kd_picking'=>$kode,
                                            'kd_brg'=>$val,
                                            'kd_rak'=>$request->input('rak')[$key],
                                            'pdos'=>$request->input('pdos')[$key],
                                            'ppcs'=>$request->input('ppcs')[$key],
                                            'dos'=>$request->input('dos')[$key],
                                            'pcs'=>$request->input('pcs')[$key],
                                            'stok_id'=>$request->input('idstok')[$key]
                                        ]
                                    );

                                $cksstok=\App\Models\Stok::find($request->input('idstok')[$key]);
                                if($cksstok!=null){
                                    if($cksstok->pcs >= $total_diinput){
                                        \DB::statement("UPDATE stok SET pcs = pcs-".$total_diinput." 
                                            where id='".$request->input('idstok')[$key]."'");
                                    }else{
                                        $cksstok->pcs=0;
                                        $cksstok->save();
                                    }
                                }
                                
                            }else{
                                $detail_no_ref_po[]=array(
                                    'no_po'=>request('no_po'),
                                    'kd_brg'=>$val,
                                    'dos'=>$final_dos,
                                    'pcs'=>$final_pcs,
                                    'total_pcs'=>$final_total_diinput,
                                    'lokasi_id'=>request('lokasi'),
                                    'jumlah'=>$final_total_diinput
                                );

                                \DB::table('rpicking')
                                    ->insert(
                                        [
                                            'kd_picking'=>$kode,
                                            'kd_brg'=>$val,
                                            'kd_rak'=>$request->input('rak')[$key],
                                            'pdos'=>$request->input('pdos')[$key],
                                            'ppcs'=>$request->input('ppcs')[$key],
                                            'dos'=>$request->input('dos')[$key],
                                            'pcs'=>$request->input('pcs')[$key],
                                            'stok_id'=>$request->input('idstok')[$key]
                                        ]
                                    );

                                $cksstok=\App\Models\Stok::find($request->input('idstok')[$key]);
                                if($cksstok!=null){
                                    if($cksstok->pcs >= $total_diinput){
                                        \DB::statement("UPDATE stok SET pcs = pcs-".$total_diinput." 
                                            where id='".$request->input('idstok')[$key]."'");
                                    }else{
                                        $cksstok->pcs=0;
                                        $cksstok->save();
                                    }
                                }
                            }

                        }else{
                            \DB::table('rpicking')
                                ->insert(
                                    [
                                        'kd_picking'=>$kode,
                                        'kd_brg'=>$val,
                                        'kd_rak'=>$request->input('rak')[$key],
                                        'pdos'=>$request->input('pdos')[$key],
                                        'ppcs'=>$request->input('ppcs')[$key],
                                        'dos'=>$request->input('dos')[$key],
                                        'pcs'=>$request->input('pcs')[$key],
                                        'stok_id'=>$request->input('idstok')[$key]
                                    ]
                                );

                            $cksstok=\App\Models\Stok::find($request->input('idstok')[$key]);
                            if($cksstok!=null){
                                if($cksstok->pcs >= $totalpcnya){
                                    \DB::statement("UPDATE stok SET pcs = pcs-".$totalpcnya." 
                                        where id='".$request->input('idstok')[$key]."'");
                                }else{
                                    $cksstok->pcs=0;
                                    $cksstok->save();
                                }
                            }
                        }
                    }

                    if(count($detail_no_ref_po)>0){
                        $poSekarang=\App\Models\Po::find(request('no_po'));
                        $kodepo=$this->autonumber_po();
                        
                        $cus=new \App\Models\Po;
                        $cus->no_po=$kodepo;
                        $cus->no_ref_po=request('no_po');
                        $cus->customer_id=$poSekarang->customer_id;
                        $cus->ket=$poSekarang->ket;
                        $cus->tgl=date('Y-m-d');
                        $cus->lokasi_id=request('lokasi');
                        $cus->perusahaan_id=auth()->user()->perusahaan_id;
                        $cus->insert_user=auth()->user()->username;
                        $cus->update_user=auth()->user()->username;
                        $simpancus=$cus->save();

                        if($simpancus){
                            foreach($detail_no_ref_po as $row)
                            {
                                \DB::table('rpo')
                                    ->insert(
                                        [
                                            'no_po'=>$kodepo,
                                            'kd_brg'=>$row['kd_brg'],
                                            'dos'=>$row['dos'],
                                            'pcs'=>$row['pcs'],
                                            'total_pcs'=>$row['total_pcs'],
                                            'lokasi_id'=>$row['lokasi_id'],
                                            'jumlah'=>$row['jumlah']
                                        ]
                                    );   
                            }
                        }
                    }
                }

                $nota=Picking::with(
                    [
                        'detail',
                        'po',
                        'po.customer',
                        'po.lokasi',
                        'perusahaan'
                    ]
                )->find($kode);

                $data=array(
                    'success'=>true,
                    'pesan'=>'Data berhasil disimpan',
                    'errors'=>'',
                    'adahutang'=>false,
                    'nota'=>$nota
                );
            }else{
                $data=array(
                    'success'=>false,
                    'pesan'=>'Data gagal disimpan',
                    'errors'=>'',
                    'adahutang'=>false
                );
            }
        }

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Master\Picking  $picking
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $picking=Picking::with(
            [
                'po',
                'po.customer',
                'po.lokasi',
                'perusahaan',
                'detail'
            ]
        )->find($id);

        $lis=\DB::select("select semua.kd_picking,semua.kd_brg, semua.nm, 
            semua.jual as harga,sum(semua.dos) as dos, sum(semua.pcs) as pcs,
            sum(semua.total_pcs) as jumlah, sum(semua.total_harga) as subtotal,
            'old' as status
            from 
            (select a.kd_picking,a.kd_brg, a.kd_rak, b.nm, b.pcs as pcs_barang,a.dos, a.pcs,b.jual,
            (a.dos * b.pcs + a.pcs) as total_pcs,
            (b.jual * (a.dos * b.pcs + a.pcs)) as total_harga
            from rpicking a 
            left join brg b on b.kd=a.kd_brg
            where a.kd_picking='$id')
            as semua
            group by semua.kd_brg");

        $picking->hitungan=$lis;

        return $picking;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Picking  $picking
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
     * @param  \App\Master\Picking  $picking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Master\Picking  $picking
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $picking=Picking::find($id);

        $hapus=$picking->delete();

        if($hapus){
            \DB::table('rpicking')
                ->where('kd_picking',$id)
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

    public function autonumber_picking()
    {
        $perusahaan=auth()->user()->perusahaan->nama;

        $sql=Picking::select(\DB::Raw("max(kd_picking) as maxKode"))
            ->where('perusahaan_id',auth()->user()->perusahaan_id)
            ->where('kd_picking','like','PCK-'.$perusahaan.'%')
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 11,11);
        $noUrut++;
        $char = "PCK-".$perusahaan."-".date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }

    public function autonumber_po()
    {
        $perusahaan=auth()->user()->perusahaan->nama;

        $sql=Po::select(\DB::Raw("max(no_po) as maxKode"))
            ->where('no_po','like','PO-'.$perusahaan.'%')
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 10,10);
        $noUrut++;
        $char = "PO-".$perusahaan."-".date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }

    public function list_picking_not_in_order(Request $request)
    {
        $po=\DB::table('picking as a')
            ->leftJoin('po as b','b.no_po','=','a.no_po')
            ->leftJoin('customer as c','b.customer_id','=','c.kd')
            ->whereRaw("kd_picking not in (select kd_picking from orders)")
            ->select('a.*','c.nm','c.nm_toko')
            ->where('a.perusahaan_id',auth()->user()->perusahaan_id)
            ->get();

        return $po;
    }

    public function autonumber_po_old()
    {
        $sql=Po::select(\DB::Raw("max(no_po) as maxKode"))
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 10,10);
        $noUrut++;
        $char = "PO-OLD-".date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }
}
