<?php

namespace App\Http\Controllers\Master;

use App\Models\Storing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StoringController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $storing=Storing::with(
            [
                'detail',
                'lokasi'
            ]
        );

        if($request->has('q')){
            $storing=$storing->where('no_storing','like','%'.request('q').'%')
                ->orWhere('no_ref','like','%'.request('q').'%');
        }

        $storing=$storing->paginate(25);

        return $storing;
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
            'no_ref'=>'required',
            'tanggal'=>'required',
            'lokasi'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'error'=>$validasi->errors()->all()
            );
        }else{
            $s=new Storing;
            $s->no_storing=request('kode');
            $s->no_ref=request('no_ref');
            $s->tgl=date('Y-m-d',strtotime(request('tanggal')));
            $s->lokasi_id=request('lokasi');

            $simpan=$s->save();

            if($simpan){
                if($request->has('listBarang')){
                    $listbarang=request('listBarang');

                    foreach($listbarang as $key=>$val){
                        \DB::table('rstoring')
                            ->insert(
                                [
                                    'no_storing'=>request('kode'),
                                    'kd_brg'=>$val['kd_barang'],
                                    'rak_id'=>$val['rak'],
                                    'dos'=>$val['dos'],
                                    'pcs'=>$val['pcs']
                                ]
                            );

                        //cek di tabel stok ada gak barang ini
                        $cek=\DB::table('stok')
                            ->where('kd_brg',$val['kd_barang'])
                            ->where('lokasi_id',request('lokasi'))
                            ->where('rak_id',$val['rak'])
                            ->count();

                        if($cek > 0){
                            \DB::statement("UPDATE stok SET pcs = pcs+".$val['pcs']." , updated_at='".date('Y-m-d H:i:s')."', tgl='".date('Y-m-d')."'
                                            where kd_brg='".$val['kd_barang']."' 
                                            and lokasi_id='".request('lokasi')."' and rak_id='".$val['rak']."'");
                        }else{
                            \DB::table('stok')
                                ->insert(
                                    [
                                        'kd_brg'=>$val['kd_barang'],
                                        'lokasi_id'=>request('lokasi'),
                                        'rak_id'=>$val['rak'],
                                        'pcs'=>$val['pcs'],
                                        'tgl'=>date('Y-m-d'),
                                        'created_at'=>date('Y-m-d H:i:s'),
                                        'updated_at'=>date('Y-m-d H:i:s')
                                    ]
                                );
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
     * @param  \App\Master\Storing  $storing
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $storing=Storing::with(
            [
                'detail',
                'lokasi'
            ]
        )->find($id);

        return $storing;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Storing  $storing
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
     * @param  \App\Master\Storing  $storing
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Master\Storing  $storing
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $storing=Storing::find($id);

        $hapus=$storing->delete();

        if($hapus){
            \DB::table('rstoring')
                ->where('no_storing',$id)
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

    public function stok_di_rak(Request $request)
    {
        $rules=[
            'barang'=>'required',
            'rak'=>'required',
            'lokasi'=>'required'
        ];


        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            return array(
                'success'=>false,
                'pcs'=>'error'
            );
        }else{
            $stok=\DB::Table('stok')
                ->where('kd_brg',request('barang'))
                ->where('rak_id',request('rak'))
                ->where('lokasi_id',request('lokasi'))
                ->get();

            if(count($stok)>0){
                return array(
                    'success'=>true,
                    'pcs'=>$stok[0]->pcs
                );
            }else{
                return array(
                    'success'=>false,
                    'pcs'=>0
                );
            }
        }
    }
}
