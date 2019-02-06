<?php

namespace App\Http\Controllers\Master;

use App\Models\Rak;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RakController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rak=Rak::with('lokasi')->paginate(25);

        return $rak;
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
            'lokasi'=>'required'
        ];

        $validasi=\Validator::make(request()->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Data berhasil dihapus',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $rak=new Rak;
            $rak->nm=request('nama');
            $rak->lokasi_id=request('lokasi');
            $rak->save();

            $data=array(
                'success'=>true,
                'pesan'=>'data berhasil disimpan',
                'errors'=>array()
            );
        }

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Master\Rak  $rak
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rak=Rak::find($id);

        return $rak;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Rak  $rak
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
     * @param  \App\Master\Rak  $rak
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules=[
            'nama'=>'required',
            'lokasi'=>'required'
        ];

        $validasi=\Validator::make(request()->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Data berhasil dihapus',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $rak=Rak::find($id);
            $rak->nm=request('nama');
            $rak->lokasi_id=request('lokasi');
            $rak->save();

            $data=array(
                'success'=>true,
                'pesan'=>'data berhasil disimpan',
                'errors'=>array()
            );
        }

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Master\Rak  $rak
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rak=Rak::find($id);

        $hapus=$rak->delete();

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
