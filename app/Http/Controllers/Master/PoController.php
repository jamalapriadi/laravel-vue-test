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
                'perusahaan'
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
            'customer'=>'required'
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
                                    'pcs'=>$val['pcs']
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
                'perusahaan'
            ]
        )->find($id);

        return $po;
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
        $po=\DB::table('po')
            ->whereRaw("no_po not in (select no_po from picking)")
            ->get();

        return $po;
    }
}
