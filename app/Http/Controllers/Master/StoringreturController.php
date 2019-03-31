<?php

namespace App\Http\Controllers\Master;

use App\Models\Storingretur;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoringreturController extends Controller
{
    public function index(Request $request)
    {
        $r=Storingretur::with(
            [
                'detail',
                'lokasi'
            ]
        );

        if($request->has('q')){
            $r=$r->where('no_storing','like','%'.request('q').'%')
                ->orWhere('no_retur','like','%'.request('q').'%');
        }

        $r=$r->paginate(25);

        return $r;
    }

    public function store(Request $request)
    {
        $rules=[
            'kode'=>'required',
            'no_retur'=>'required',
            'tanggal'=>'required',
            'lokasi'=>'required',
            'detail'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $s=new Storingretur;
            $s->no_storing=request('kode');
            $s->no_retur=request('no_retur');
            $s->tgl=date('Y-m-d',strtotime(request('tanggal')));
            $s->lokasi_id=request('lokasi');
            $s->insert_user=auth()->user()->username;
            $s->update_user=auth()->user()->username;

            $simpan=$s->save();

            if($simpan){
                if($request->has('detail')){
                    $detail=request('detail');
                    $rak=request('rak');

                    foreach($detail as $key=>$val){
                        $cekB=\App\Models\Barang::find($val['kd']);

                        \DB::table('rstoring_retur')
                            ->insert(
                                [
                                    'no_storing_retur'=>request('kode'),
                                    'kd_brg'=>$val['kd'],
                                    'rak_id'=>$rak[$key],
                                    'dos'=>$val['dos'],
                                    'pcs'=>$val['pcs']
                                ]
                            );

                        //update stok
                        $jumlah = ($cekB->pcs * $val['dos']) + $val['pcs'];
                        
                        // \DB::statement("UPDATE stok SET pcs = pcs+".$jumlah." 
                        // where kd_brg='".$val['kd']."' and rak_id='".$rak[$key]."' and lokasi_id='".request('lokasi')."'");

                        \DB::table('stok')
                            ->insert(
                                [
                                    'kd_brg'=>$val['kd'],
                                    'rak_id'=>$rak[$key],
                                    'lokasi_id'=>request('lokasi'),
                                    'tgl'=>date('Y-m-d'),
                                    'pcs'=>$jumlah,
                                    'created_at'=>date('Y-m-d H:i:s'),
                                    'updated_at'=>date('Y-m-d H:i:s')
                                ]
                            );
                    }
                }

                $data=array(
                    'success'=>true,
                    'pesan'=>'Data berhasil disimpan',
                    'errors'=>array()
                );
            }else{
                $data=array(
                    'success'=>false,
                    'pesan'=>'Data gagal disimpan',
                    'errors'=>array()
                );
            }
        }

        return $data;
    }

    public function show($id)
    {
        $r=Storingretur::with(
            [
                'detail',
                'lokasi'
            ]
        )->find($id);

        return $r;
    }

    public function update(Request $request,$id)
    {

    }

    public function destroy(Request $request, $id)
    {
        $storing=Storingretur::find($id);

        $hapus=$storing->delete();

        if($hapus){
            \DB::table('rstoring_retur')
                ->where('no_storing_retur',$id)
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

    public function autonumber_storing_retur(Request $request)
    {
        $sql=Storingretur::select(\DB::Raw("max(no_storing) as maxKode"))
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 11,11);
        $noUrut++;
        $char = "SRR-TLG-".date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }

    public function list_retur_not_in_storing(Request $request)
    {
        $lis=\DB::select("SELECT a.no_retur FROM retur a
            WHERE a.no_retur NOT IN (SELECT aa.no_retur FROM storing_retur aa)");

        return $lis;
    }

    public function no_retur_by_id(Request $request,$id)
    {
        $r=\App\Models\Retur::with(
            [
                'order',
                'detail',
                'customer'
            ]
        )->find($id);

        return $r;
    }
}