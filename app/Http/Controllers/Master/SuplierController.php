<?php

namespace App\Http\Controllers\Master;

use App\Models\Suplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SuplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $suplier=Suplier::select('kd','nm','alamat',
        'kota_id','telepon','nm_perusahaan','kontak','fax')
            ->with('kota');

        if($request->has('q')){
            $suplier=$suplier->where('nm','alamat','%'.request('q').'%')
                ->orWhere('telepon','like','%'.request('q').'%')
                ->orWhere('nm_perusahaan','like','%'.request('q').'%')
                ->orWhere('kontak','like','%'.request('q').'%')
                ->orWhere('fax','like','%'.request('q').'%');
        }

        $suplier=$suplier->paginate(25);

        return $suplier;
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
            'alamat'=>'required',
            'kota'=>'required',
            'telepon'=>'required',
            'perusahaan'=>'required',
            'kontak'=>'required',
            'fax'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi gagal',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $suplier=new Suplier;
            $suplier->nm=request('nama');
            $suplier->alamat=request('alamat');
            $suplier->kota_id=request('kota');
            $suplier->telepon=request('telepon');
            $suplier->nm_perusahaan=request('perusahaan');
            $suplier->kontak=request('kontak');
            $suplier->fax=request('fax');
            $suplier->save();

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
     * @param  \App\Master\Suplier  $suplier
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $suplier=Suplier::find($id);

        return $suplier;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Suplier  $suplier
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
     * @param  \App\Master\Suplier  $suplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules=[
            'nama'=>'required',
            'alamat'=>'required',
            'kota'=>'required',
            'telepon'=>'required',
            'perusahaan'=>'required',
            'kontak'=>'required',
            'fax'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi gagal',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $suplier=Suplier::find($id);
            $suplier->nm=request('nama');
            $suplier->alamat=request('alamat');
            $suplier->kota_id=request('kota');
            $suplier->telepon=request('telepon');
            $suplier->nm_perusahaan=request('perusahaan');
            $suplier->kontak=request('kontak');
            $suplier->fax=request('fax');
            $suplier->save();

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
     * @param  \App\Master\Suplier  $suplier
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $suplier=Suplier::find($id);

        $hapus=$suplier->delete();

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
