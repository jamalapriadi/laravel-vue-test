<?php

namespace App\Http\Controllers\Master;

use App\Models\Rak;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RakExport;
use App\Imports\RakImport;

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
            'raks'=>'required',
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
            $raks=request('raks');
            foreach($raks as $key=>$val){
                $rak=new Rak;
                $rak->lokasi_id=request('lokasi');
                $rak->nm=$val;
                $rak->save();
            }

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

    public function list_rak(Request $request)
    {
        $rak=Rak::select('kd','nm','lokasi_id');

        if($request->has('lokasi')){
            $rak=$rak->where('lokasi_id',$request->input('lokasi'));
        }

        $rak=$rak->get();

        return $rak;
    }

    public function sample_rak(Request $request)
    {
        return Excel::download(new RakExport, 'rak.xlsx');
    }

    public function import_new_rak(Request $request)
    {
        $rules=['file'=>'required'];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi Gagal',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            Excel::import(new RakImport, request()->file('file'));

            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil di import',
                'errors'=>array()
            );
        }

        return $data;
    }
}
