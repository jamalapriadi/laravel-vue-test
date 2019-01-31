<?php

namespace App\Http\Controllers\Master;

use App\Models\Kelompok;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KelompokController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kelompok=Kelompok::paginate(25);

        return $kelompok;
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
        $rules=['nama'=>'required|max:30'];

        $validasi=\Validator::make(request()->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $kel=new Kelompok;
            $kel->nm=request('nama');
            $kel->save();

            $data=array(
                'success'=>true,
                'pesan'
            );
        }

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Master\Kelompok  $kelompok
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kel=Kelompok::find($id);

        return $kel;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Kelompok  $kelompok
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Master\Kelompok  $kelompok
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules=['nama'=>'required|max:30'];

        $validasi=\Validator::make(request()->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $kel=Kelompok::find($id);
            $kel->nm=request('nama');
            $kel->save();

            $data=array(
                'success'=>true,
                'pesan'
            );
        }

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Master\Kelompok  $kelompok
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $kel=Kelompok::find($id);

        $hapus=$kel->delete();

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
