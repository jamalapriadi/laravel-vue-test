<?php

namespace App\Http\Controllers\Master;

use App\Models\Mutasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MutasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mutasi=Mutasi::with(
            [
                'detail',
                'gudang_lama',
                'gudang_baru'
            ]
        )->where('perusahaan_id',auth()->user()->perusahaan_id);

        // if($request->has('q')){
        //     $mutasi=$mutasi->where('no_storing','like','%'.request('q').'%')
        //         ->orWhere('no_ref','like','%'.request('q').'%');
        // }

        $mutasi=$mutasi->paginate(25);

        return $mutasi;
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
            'gudang_lama'=>'required',
            'tanggal'=>'required',
            'gudang_baru'=>'required',
            'listBarang'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'error'=>$validasi->errors()->all()
            );
        }else{
            $kode=$this->autonumber_mutasi();

            $s=new Mutasi;
            $s->no_mutasi=$kode;
            $s->lokasil=request('gudang_lama')['id'];
            $s->lokasib=request('gudang_baru')['id'];
            $s->tgl=date('Y-m-d',strtotime(request('tanggal')));
            $s->ket=request('keterangan');
            $s->perusahaan_id=auth()->user()->perusahaan_id;
            $s->insert_user=auth()->user()->username;

            $simpan=$s->save();

            if($simpan){
                if($request->has('listBarang')){
                    $listbarang=request('listBarang');

                    foreach($listbarang as $key=>$val){
                        \DB::table('rmutasi')
                                ->insert(
                                    [
                                        'no_mutasi'=>$kode,
                                        'kd_brg'=>$val['kd_barang'],
                                        'rakl'=>$val['rak_lama_id'],
                                        'rakb'=>$val['rak_baru_id'],
                                        'dos'=>$val['dos'],
                                        'pcs'=>$val['pcs']
                                    ]
                                );

                        //cek di tabel stok ada gak barang ini
                        $cek=\DB::table('stok')
                            ->where('kd_brg',$val['kd_barang'])
                            ->where('lokasi_id',$val['gudang_lama_id'])
                            ->where('rak_id',$val['rak_lama_id'])
                            ->count();

                        if($cek > 0){

                            \DB::statement("UPDATE stok SET pcs = pcs-".$val['pcs']." where kd_brg='".$val['kd_barang']."' 
                                            and lokasi_id='".$val['gudang_lama_id']."' and rak_id='".$val['rak_lama_id']."'");

                            $cekbaru=\DB::table('stok')
                                ->where('kd_brg',$val['kd_barang'])
                                ->where('lokasi_id',$val['gudang_baru_id'])
                                ->where('rak_id',$val['rak_baru_id'])
                                ->count();

                            if($cekbaru>0){
                                \DB::statement("UPDATE stok SET pcs = pcs+".$val['pcs']." where kd_brg='".$val['kd_barang']."' 
                                            and lokasi_id='".$val['gudang_baru_id']."' and rak_id='".$val['rak_baru_id']."'");
                            }else{
                                \DB::table('stok')
                                ->insert(
                                    [
                                        'kd_brg'=>$val['kd_barang'],
                                        'lokasi_id'=>$val['gudang_baru_id'],
                                        'rak_id'=>$val['rak_baru_id'],
                                        'pcs'=>$val['pcs']
                                    ]
                                );
                            }
                        }
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
     * @param  \App\Master\Mutasi  $mutasi
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mutasi=Mutasi::with(
            [
                'detail',
                'gudang_lama',
                'gudang_baru'
            ]
        )->find($id);

        return $mutasi;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Mutasi  $mutasi
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
     * @param  \App\Master\Mutasi  $mutasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Master\Mutasi  $mutasi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sql=Mutasi::find($id);

        $hapus=$sql->delete();

        if($hapus){
            \DB::table('rmutasi')
                ->where('no_mutasi',$id)
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

    public function autonumber_mutasi(Request $request)
    {
        $perusahaan=auth()->user()->perusahaan->nama;

        $sql=Mutasi::select(\DB::Raw("max(no_mutasi) as maxKode"))
            ->where('no_mutasi','like','MTS-'.$perusahaan.'%')
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 11,11);
        $noUrut++;
        $char = "MTS-".$perusahaan."-".date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }
}
