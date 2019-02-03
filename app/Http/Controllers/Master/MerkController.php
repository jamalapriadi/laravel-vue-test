<?php

namespace App\Http\Controllers\Master;

use App\Models\Merk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MerkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $merk=Merk::select('nm','persen');

        if($request->has('q')){
            $merk=$merk->where('nm','like','%'.request('q').'%')
                ->orWhere('persen','like','%'.request('q').'%');
        }

        $merk=$merk->paginate(25);

        return $merk;
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
            'persen'=>'required'
        ];

        $validasi=\Validator::make(request()->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $merk=new Merk;
            $merk->nm=request('nama');
            $merk->persen=request('persen');
            $merk->save();

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
     * @param  \App\Master\Merk  $merk
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $merk=Merk::find($id);

        return $merk;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Merk  $merk
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
     * @param  \App\Master\Merk  $merk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules=[
            'nama'=>'required',
            'persen'=>'required'
        ];

        $validasi=\Validator::make(request()->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $merk=Merk::find($id);
            $merk->nm=request('nama');
            $merk->persen=request('persen');
            $merk->save();

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
     * @param  \App\Master\Merk  $merk
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $merk=Merk::find($id);

        $hapus=$merk->delete();

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

    public function list_merk()
    {
        $merk=Merk::select('id','nm','persen')->get();

        return $merk;
    }
}
