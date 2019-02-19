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
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $p=new Picking;
            $p->kd_picking=request('kode');
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

                    if($statuskurang=="N"){
                        $posekarang=Po::find(request('no_po'));

                        $cus=new Po;
                        $cus->no_po=$this->autonumber_po();
                        $cus->no_ref_po=request('no_po');
                        $cus->customer_id=$posekarang->customer_id;
                        $cus->ket=$posekarang->ket;
                        $cus->tgl=$posekarang->tgl;
                        $cus->lokasi_id=$posekarang->lokasi_id;
                        $cus->perusahaan_id=auth()->user()->perusahaan_id;
                        $cus->insert_user=auth()->user()->username;
                        $cus->update_user=auth()->user()->username;
                        $simpancus=$cus->save();

                        if($simpancus){
                            if($request->has('kurang')){
                                $kurang=request('kurang');
            
                                foreach($kurang as $key=>$val){
                                    \DB::table('rpo')
                                        ->insert(
                                            [
                                                'no_po'=>$cus->no_po,
                                                'kd_brg'=>$val['kd'],
                                                'dos'=>$val['dos'],
                                                'pcs'=>$val['kurang_nya']
                                            ]
                                        );
                                }
                            }
                        }
                    }
                }

                if($request->has('kodes')){
                    $kodes=request('kodes');

                    foreach($kodes as $key=>$val){
                        \DB::table('rpicking')
                            ->insert(
                                [
                                    'kd_picking'=>request('kode'),
                                    'kd_brg'=>$val,
                                    'kd_rak'=>$request->input('rak')[$key],
                                    'pdos'=>$request->input('pdos')[$key],
                                    'ppcs'=>$request->input('ppcs')[$key],
                                    'dos'=>$request->input('dos')[$key],
                                    'pcs'=>$request->input('pcs')[$key]
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
        $sql=Picking::select(\DB::Raw("max(kd_picking) as maxKode"))
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 11,11);
        $noUrut++;
        $char = "PCK-TLG-".date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
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

    public function list_picking_not_in_order(Request $request)
    {
        $po=\DB::table('picking')
            ->whereRaw("kd_picking not in (select kd_picking from orders)")
            ->get();

        return $po;
    }
}