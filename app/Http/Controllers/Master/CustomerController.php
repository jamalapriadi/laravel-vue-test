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
        $customer=Customer::with('kota','jenisnya');

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
        return $request->all();
        $rules=[
            'nama'=>'required',
            'jenis_customer'=>'required',
            'toko'=>'required',
            'nik'=>'required',
            'npwp'=>'required',
            'alamat'=>'required',
            'alias'=>'required',
            'kota'=>'required',
            'telepon'=>'required',
            // 'nmtk'=>'required',
            // 'kontak'=>'required',
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
            $userid=auth()->user()->id;

            $user=\App\User::with(
                [
                    'perusahaan',
                    'perusahaan.ket'
                ]
            )->find($userid);

            $cus=new Customer;
            $cus->kd=str_slug(request('nama')." ".$user->perusahaan->nama,'_');
            $cus->jenis_customer=request('jenis_customer');
            $cus->nm_toko=request('toko');
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
            $cus->perusahaan_id=auth()->user()->perusahaan_id;
            $cus->insert_user=auth()->user()->username;
            $simpan=$cus->save();

            if($simpan){
                if($request->has('jenis_customer')){
                    $jenis_customer=request('jenis_customer');

                    foreach($jenis_customer as $row){
                        \DB::table('customer_detail_jenis')
                            ->insert(
                                [
                                    'customer_id'=>$cus->kd,
                                    'jenis_customer_id'=>$row->jns_customer
                                ]
                            );
                    }
                }
            }

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
        $cus=Customer::with('kota','jenisnya')->find($id);

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
            'jenis_customer'=>'required',
            'toko'=>'required',
            'nik'=>'required',
            'npwp'=>'required',
            'alamat'=>'required',
            'alias'=>'required',
            'kota'=>'required',
            'telepon'=>'required',
            // 'nmtk'=>'required',
            // 'kontak'=>'required',
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
            $cus->nm_toko=request('toko');
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
            $cus->perusahaan_id=auth()->user()->perusahaan_id;
            $cus->insert_user=auth()->user()->username;
            $simpan=$cus->save();

            if($simpan){
                if($request->has('jenis_customer')){
                    $jenis_customer=request('jenis_customer');

                    //hapus data lama
                    \DB::table('customer_detail_jenis')
                        ->where('customer_id',$id)
                        ->delete();

                    foreach($jenis_customer as $row){
                        \DB::table('customer_detail_jenis')
                            ->insert(
                                [
                                    'customer_id'=>$cus->kd,
                                    'jenis_customer_id'=>$row['jns_customer']
                                ]
                            );
                    }
                }
            }

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

    public function autonumber_customer()
    {
        $sql=Customer::select(\DB::Raw("max(kd) as maxKode"))
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 3,3);
        $noUrut++;
        $char = "CST";
        $newId= $char.sprintf("%03s",$noUrut);

        return $newId;
    }

    public function list_customer(Request $request)
    {
        $customer=Customer::with('kota')
            ->where('perusahaan_id',auth()->user()->perusahaan_id);

        if($request->has('q')){
            $customer=$customer->where('nm','like','%'.request('q').'%');
        }

        $customer=$customer->get();

        return $customer;
    }

    public function cari_customer_by_nama(Request $request)
    {
        $cus=Customer::select('kd','nm','nm_toko','saldo','perusahaan_id');

        if($request->has('q')){
            $cus=$cus->where('nm','like','%'.request('q').'%');
        }

        $cus=$cus->where('perusahaan_id',auth()->user()->perusahaan_id)
            ->whereNotNull('perusahaan_id')
            ->get();

        return $cus;
    }

    public function customer_not_in_picking(Request $request)
    {
        $perusahaan_id=auth()->user()->perusahaan_id;

        if($request->has('status')){
            $status=$request->input('status');

            if($status=="false"){
                $lis=\DB::select("SELECT a.customer_id, b.nm 
                    FROM po a
                    LEFT JOIN customer b ON b.kd=a.customer_id
                    WHERE a.no_ref_po IS NOT NULL
                    AND b.perusahaan_id=$perusahaan_id
                    GROUP BY a.customer_id");
            }else{
                $lis=\DB::select("SELECT a.customer_id, b.nm 
                    FROM po a
                    LEFT JOIN customer b ON b.kd=a.customer_id
                    WHERE a.no_po NOT IN (SELECT no_po FROM picking)
                    AND b.perusahaan_id=$perusahaan_id
                    GROUP BY a.customer_id");
            }
        }

        return $lis;
    }

    public function update_customer(){
        $cus=Customer::whereNotNull('jenis_customer')->get();
        foreach($cus as $row){
            \DB::table('customer_detail_jenis')
                ->insert(
                    [
                        'customer_id'=>$row->kd,
                        'jenis_customer_id'=>$row->jenis_customer
                    ]
                );
        }

        return "sukses";
    }
}
