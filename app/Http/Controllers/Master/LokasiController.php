<?php

namespace App\Http\Controllers\Master;

use App\Models\Lokasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LokasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $lokasi=Lokasi::select('id','lokasi','nm');

        if($request->has('q')){
            $lokasi=$lokasi->where('nm','like','%'.request('q').'%')
                ->orWhere('lokasi','like','%'.request('q').'%');
        }

        $lokasi=$lokasi->paginate(25);

        return $lokasi;
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
            'lokasi'=>'required',
            'nama'=>'required'
        ];

        $validasi=\Validator::make(request()->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>"Validasi errors",
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $lokasi=new Lokasi;
            $lokasi->lokasi=request('lokasi');
            $lokasi->nm=request('nama');
            $lokasi->save();

            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil disimpan',
                'errors'=>array()
            );
        }

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Master\Lokasi  $lokasi
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $lokasi=Lokasi::find($id);

        return $lokasi;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Lokasi  $lokasi
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
     * @param  \App\Master\Lokasi  $lokasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules=[
            'lokasi'=>'required',
            'nama'=>'required'
        ];

        $validasi=\Validator::make(request()->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>"Validasi errors",
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $lokasi=Lokasi::find($id);
            $lokasi->lokasi=request('lokasi');
            $lokasi->nm=request('nama');
            $lokasi->save();

            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil disimpan',
                'errors'=>array()
            );
        }

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Master\Lokasi  $lokasi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $lokasi=Lokasi::find($id);

        $hapus=$lokasi->delete();

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

    public function list_lokasi()
    {
        $lokasi=Lokasi::select('id','lokasi','nm');

        $lokasi=$lokasi->get();

        return $lokasi;
    }
}
