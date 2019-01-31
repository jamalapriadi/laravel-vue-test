<?php

namespace App\Http\Controllers\Master;

use App\Models\Bank;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bank=Bank::paginate(25);

        return $bank;
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
            'kode'=>'required|max:5',
            'nama'=>'required|max:30',
            'status'=>'required|max:15'
        ];

        $validasi=\Validator::make(request()->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $bank=new Bank;
            $bank->kd_bank=request('kode');
            $bank->nm=request('nama');
            $bank->status=request('status');

            $bank->save();

            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil disimpan',
                'errrors'=>array()
            );
        }

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Master\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $bank=Bank::find($id);

        return $bank;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Bank  $bank
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
     * @param  \App\Master\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules=[
            'kode'=>'required|max:5',
            'nama'=>'required|max:30',
            'status'=>'required|max:15'
        ];

        $validasi=\Validator::make(request()->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $bank=Bank::find($id);
            $bank->kd_bank=request('kode');
            $bank->nm=request('nama');
            $bank->status=request('status');

            $bank->save();

            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil diupdate',
                'errrors'=>array()
            );
        }

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Master\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bank=Bank::find($id);

        $hapus=$bank->delete();

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
