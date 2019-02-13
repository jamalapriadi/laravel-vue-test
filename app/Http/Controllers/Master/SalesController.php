<?php

namespace App\Http\Controllers\Master;

use App\Models\Sales;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sales=Sales::select('id','nm','status');

        if($request->has('q')){
            $sales=$sales->where('nm','like','%'.request('q').'%');
        }

        $sales=$sales->paginate(25);

        return $sales;
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
            'status'=>'required'
        ];

        $validasi=\Validator::make(request()->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $sales=new Sales;
            $sales->nm=request('nama');
            $sales->status=request('status');
            $sales->save();

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
     * @param  \App\Master\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $sales=Sales::find($id);

        return $sales;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Sales  $sales
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
     * @param  \App\Master\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules=[
            'nama'=>'required',
            'status'=>'required'
        ];

        $validasi=\Validator::make(request()->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $sales=Sales::find($id);
            $sales->nm=request('nama');
            $sales->status=request('status');
            $sales->save();

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
     * @param  \App\Master\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sales=Sales::find($id);

        $hapus=$sales->delete();

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

    public function list_sales(Request $request)
    {
        $sales=Sales::select('id','nm','status');

        if($request->has('q')){
            $sales=$sales->where('nm','like','%'.request('q').'%');
        }

        $sales=$sales->get();

        return $sales;
    }
}
