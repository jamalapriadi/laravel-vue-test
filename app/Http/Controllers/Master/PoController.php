<?php

namespace App\Http\Controllers\Master;

use App\Models\Po;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $po=Po::with(
            [
                'detail',
                'customer',
                'perusahaan',
                'lokasi'
            ]
        )->where('perusahaan_id',auth()->user()->perusahaan_id);

        if($request->has('q')){
            $po=$po->where('customer_id','like','%'.request('q').'%');
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
            'customer'=>'required',
            'lokasi'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi error',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $cus=new Po;
            $cus->no_po=request('kode');
            $cus->customer_id=request('customer');
            $cus->ket=request('keterangan');
            $cus->tgl=date('Y-m-d',strtotime(request('tanggal')));
            $cus->lokasi_id=request('lokasi');
            $cus->perusahaan_id=auth()->user()->perusahaan_id;
            $cus->insert_user=auth()->user()->username;
            $cus->update_user=auth()->user()->username;
            $simpan=$cus->save();

            if($simpan){
                if($request->has('listBarang')){
                    $listbarang=request('listBarang');

                    foreach($listbarang as $key=>$val){
                        \DB::table('rpo')
                            ->insert(
                                [
                                    'no_po'=>request('kode'),
                                    'kd_brg'=>$val['kd_barang'],
                                    'dos'=>$val['dos'],
                                    'pcs'=>$val['pcs'],
                                    'total_pcs'=>$val['total_pcs']
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

            
        }

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Master\Po  $po
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $po=Po::with(
            [
                'detail',
                'customer',
                'perusahaan',
                'lokasi'
            ]
        )->find($id);

        return $po;
    }

    public function po_by_id(Request $request,$id)
    {
        $po=Po::with(
            [
                'detail',
                'detail.stok',
                'customer',
                'perusahaan',
                'lokasi'
            ]
        )->find($id);

        $list=array();
        $jumlah=0;
        $rak=array();
        foreach($po->detail as $row){
            $pcs=$row->pivot->total_pcs;
            $jumlah=0;
            $sisa=$row->pivot->total_pcs;
            $diambil=0;
            
            $rowspan=1;
            if(count($row->stok)>0){
                $rowspan=count($row->stok);

                for($a=0; $a< count($row->stok); $a++){
                    if($sisa > $row->stok[$a]->pcs){
                        $diambil=$sisa;
                        $sisa=$sisa - $row->stok[$a]->pcs ;
                        $h=0;
                        if($sisa>0){
                            $h=$sisa;
                        }

                        $ekspetasi_dosnya=floor($row->pivot->total_pcs / $row->pcs);
                        $ekspetasi_pcsnya=$row->pivot->total_pcs % $row->pcs;
                        $realisasi_dosnya=0;
                        $realisasi_pcsnya=0;

                        if($row->stok[$a]->pcs > 0){
                            if($row->stok[$a]->pcs > $row->pcs){
                                if($row->stok[$a]->pcs - $row->pivot->total_pcs > 0){
                                    $realisasi_pcsnya=$row->stok[$a]->pcs - $row->pivot->total_pcs;
                                }else{
                                    $realisasi_pcsnya=0;
                                }
                            }else{
                                $realisasi_pcsnya=0;
                            }
                            
                            $realisasi_dosnya=floor($row->stok[$a]->pcs/$row->pcs);
                        }

                        $rak[]=array(
                            'kd'=>$row->kd,
                            'nm'=>$row->nm,
                            'jual'=>$row->jual,
                            'dos'=>$row->pivot->dos,
                            'ekspetasi_dosnya'=>$ekspetasi_dosnya,
                            'ekspetasi_pcsnya'=>$ekspetasi_pcsnya,
                            'realisasi_dosnya'=>$realisasi_dosnya,
                            'realisasi_pcsnya'=>$realisasi_pcsnya,
                            'pcs'=>$row->pivot->pcs,
                            'total_pcs'=>$row->pivot->total_pcs,
                            'pcsnya'=>$diambil - $h,
                            'stok'=>$row->stok[$a]->pcs,
                            'diambil'=>$diambil,
                            'kurang'=>$h,
                            'rak'=>$row->stok[$a]->rak_id
                        );


                    }else{
                        $diambil=$sisa;
                        $sisa=$diambil - $row->stok[$a]->pcs;
                        $h=0;
                        if($sisa>0){
                            $h=$sisa;
                        }

                        $ekspetasi_dosnya=floor($row->pivot->total_pcs / $row->pcs);
                        $ekspetasi_pcsnya=$row->pivot->total_pcs % $row->pcs;
                        $realisasi_dosnya=0;
                        $realisasi_pcsnya=0;

                        if($row->stok[$a]->pcs > 0){
                            if($row->stok[$a]->pcs > $row->pcs){
                                if($row->stok[$a]->pcs - $row->pivot->total_pcs > 0){
                                    $realisasi_pcsnya=$row->stok[$a]->pcs - $row->pivot->total_pcs;
                                }else{
                                    $realisasi_pcsnya=0;
                                }
                            }else{
                                $realisasi_pcsnya=0;
                            }
                            $realisasi_dosnya=floor($row->stok[$a]->pcs/$row->pcs);
                        }

                        $rak[]=array(
                            'kd'=>$row->kd,
                            'nm'=>$row->nm,
                            'jual'=>$row->jual,
                            'dos'=>$row->pivot->dos,
                            'ekspetasi_dosnya'=>$ekspetasi_dosnya,
                            'ekspetasi_pcsnya'=>$ekspetasi_pcsnya,
                            'realisasi_dosnya'=>$realisasi_dosnya,
                            'realisasi_pcsnya'=>$realisasi_pcsnya,
                            'pcs'=>$row->pivot->pcs,
                            'total_pcs'=>$row->pivot->total_pcs,
                            'pcsnya'=>$diambil - $h,
                            'stok'=>$row->stok[$a]->pcs,
                            'diambil'=>$diambil,
                            'kurang'=>$h,
                            'rak'=>$row->stok[$a]->rak_id
                        );

                        break;
                    }
                }
            }else{
                $rak[]=array(
                    'kd'=>$row->kd,
                    'nm'=>$row->nm,
                    'jual'=>$row->jual,
                    'dos'=>$row->pivot->dos,
                    'ekspetasi_dosnya'=>floor($row->pivot->total_pcs / $row->pcs),
                    'ekspetasi_pcsnya'=>$row->pivot->total_pcs % $row->pcs,
                    'realisasi_dosnya'=>0,
                    'realisasi_pcsnya'=>0,
                    'pcs'=>$row->pivot->pcs,
                    'total_pcs'=>$row->pivot->total_pcs,
                    'pcsnya'=>0,
                    'stok'=>0,
                    'diambil'=>0,
                    'kurang'=>0,
                    'rak'=>''
                );
            }
        }

        /*cari barang terakhir dan cek apakah kurangnya masih ada yang lebih dari 0 */
        $listbarang=$rak;
        usort($listbarang, function($a, $b) {
            return $a['kurang'] <=> $b['kurang'];
        });

        $tempArr = array_unique(array_column($listbarang, 'kd'));
        $hasil=array_intersect_key($listbarang, $tempArr);

        $listKurang=array();
        foreach($hasil as $key=>$val){
            if($val['kurang'] > 0){
                array_push($listKurang,[
                    'kd'=>$val['kd'],
                    'nm'=>$val['nm'],
                    'jual'=>$val['jual'],
                    'dos'=>$val['dos'],
                    'pcs'=>$val['pcs'],
                    'total_pcs'=>$val['total_pcs'],
                    'kurang_nya'=>$val['kurang'],
                    'pcsnya'=>0,
                    'stok'=>0,
                    'diambil'=>0,
                    'kurang'=>0,
                    'rak'=>''
                ]);
            }
        }

        return array(
            'po'=>$po,
            'list'=>$rak,
            'kurang'=>$listKurang
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Po  $po
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
     * @param  \App\Master\Po  $po
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Master\Po  $po
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cus=Po::find($id);

        $hapus=$cus->delete();

        if($hapus){
            \DB::table('rpo')
                ->where('no_po',$id)
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

    public function autonumber_po()
    {
        $sql=Po::select(\DB::Raw("max(no_po) as maxKode"))
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 10,10);
        $noUrut++;
        $char = "PO-TLG-".date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }

    public function list_po_not_in_picking(Request $request)
    {
        $dimana="";

        if($request->has('status')){
            $status=$request->input('status');

            if($status=="true"){
                $dimana="and no_ref_po is null";
            }

            if($status=="false"){
                $dimana="and no_ref_po is not null";
            }
        }

        if($request->has('customer')){
            $customer=$request->input('customer');
            $dimana.=" and customer_id='".$customer."'";
        }

        $po=\DB::select("select * from po where no_po not in (select no_po from picking) $dimana");

        return $po;
    }
}
