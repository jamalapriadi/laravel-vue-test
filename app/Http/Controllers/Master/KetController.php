<?php

namespace App\Http\Controllers\Master;

use App\Models\Ket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ket=Ket::paginate(25);

        return $ket;
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
            'nohp'=>'required',
            'email'=>'required',
            'pin'=>'required',
            'npwp'=>'required'
        ];

        $validasi=\Validator::make(request()->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $ket=new Ket;
            $ket->no_hp=request('nohp');
            $ket->email=request('email');
            $ket->pin=request('pin');
            $ket->npwp=request('npwp');
            $ket->save();

            $data=array(
                'success'=>true,
                'pesan'=>'data berhasil disimpan',
                'errros'=>$validasi->errors()->all()
            );
        }

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Master\Ket  $ket
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Ket  $ket
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
     * @param  \App\Master\Ket  $ket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules=[
            'nohp'=>'required',
            'email'=>'required',
            'pin'=>'required',
            'npwp'=>'required'
        ];

        $validasi=\Validator::make(request()->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $ket=Ket::find($id);
            $ket->no_hp=request('nohp');
            $ket->email=request('email');
            $ket->pin=request('pin');
            $ket->npwp=request('npwp');
            $ket->save();

            $data=array(
                'success'=>true,
                'pesan'=>'data berhasil disimpan',
                'errros'=>$validasi->errors()->all()
            );
        }

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Master\Ket  $ket
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ket=Ket::find($id);

        $hapus=$ket->delete();

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
