<?php

namespace App\Http\Controllers\Master;

use App\Models\Kota;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kota=Kota::paginate(25);

        return $kota;
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
            'nama'=>'required',
            'jenis'=>'required'
        ];

        $validasi=\Validator::make(request()->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $kota=new Kota;
            $kota->nm=request('nama');
            $kota->jenis=request('jenis');
            $kota->save();

            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil disimpan',
                'errors'=>array()
            );
        }

        return $kota;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Master\Kota  $kota
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kota=Kota::find($id);

        return $kota;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Kota  $kota
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
     * @param  \App\Master\Kota  $kota
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules=[
            'nama'=>'required',
            'jenis'=>'required'
        ];

        $validasi=\Validator::make(request()->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $kota=Kota::find($id);
            $kota->nm=request('nama');
            $kota->jenis=request('jenis');
            $kota->save();

            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil diupdate',
                'errors'=>array()
            );
        }

        return $kota;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Master\Kota  $kota
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kota=Kota::find($id);

        $hapus=$kota->delete();

        if($hapus){
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
}
