<?php

namespace App\Http\Controllers\Master;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $customer=Customer::with('kota');

        if($request->has('q')){
            $customer=$customer->where('nm','like','%'.request('q').'%');
        }

        $customer=$customer->paginate(25);

        return $customer;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            'nik'=>'required',
            'npwp'=>'required',
            'alamat'=>'required',
            'alias'=>'required',
            'kota'=>'required',
            'telepon'=>'required',
            'nmtk'=>'required',
            'kontak'=>'required',
            'kota'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi error',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $cus=new Customer;
            $cus->nm=request('nama');
            $cus->alamat=request('alamat');
            $cus->alias=request('alias');
            $cus->kota_id=request('kota');
            $cus->tlpn=request('telepon');
            $cus->nmtk=request('nmtk');
            $cus->kontak=request('kontak');
            $cus->fax=request('fax');
            $cus->plafon=request('plafon');
            $cus->top=request('top');
            $cus->npwp=request('npwp');
            $cus->nik=request('nik');
            $cus->jenis=request('jenis');
            $cus->insert_user=auth()->user()->username;
            $cus->save();

            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil disimpan',
                'errors'=>''
            );
        }

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Master\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cus=Customer::find($id);

        return $cus;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Customer  $customer
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
     * @param  \App\Master\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules=[
            'nama'=>'required',
            'nik'=>'required',
            'npwp'=>'required',
            'alamat'=>'required',
            'alias'=>'required',
            'kota'=>'required',
            'telepon'=>'required',
            'nmtk'=>'required',
            'kontak'=>'required',
            'kota'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi error',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $cus=Customer::find($id);
            $cus->nm=request('nama');
            $cus->alamat=request('alamat');
            $cus->alias=request('alias');
            $cus->kota_id=request('kota');
            $cus->tlpn=request('telepon');
            $cus->nmtk=request('nmtk');
            $cus->kontak=request('kontak');
            $cus->fax=request('fax');
            $cus->plafon=request('plafon');
            $cus->top=request('top');
            $cus->npwp=request('npwp');
            $cus->nik=request('nik');
            $cus->jenis=request('jenis');
            $cus->insert_user=auth()->user()->username;
            $cus->save();

            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil disimpan',
                'errors'=>''
            );
        }

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Master\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cus=Customer::find($id);

        $hapus=$cus->delete();

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
