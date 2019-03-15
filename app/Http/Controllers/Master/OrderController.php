<?php

namespace App\Http\Controllers\Master;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $order=Order::with(
            [
                'picking',
                'picking.po.customer',
                'picking.sales',
                'picking.lokasi',
                'detail',
                'perusahaan',
                'sales'
            ]
        );

        if($request->has('q')){
            $order=$order->where('customer_id','like','%'.request('q').'%');
        }

        $order=$order->paginate(25);

        return $order;
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
            'stokid'=>'required',
            'kode'=>'required',
            'tanggal'=>'required',
            'kd_trans'=>'required',
            'kd_picking'=>'required',
            'lokasiid'=>'required',
            'kodehit'=>'required',
            'tanggaljt'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi error',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $cus=new Order;
            $cus->no_order=request('kode');
            $cus->kd_picking=request('kd_picking');
            $cus->kd_trans=request('kd_trans');
            $cus->tgl=date('Y-m-d',strtotime(request('tanggal')));
            $cus->tgljt=date('Y-m-d',strtotime(request('tanggaljt')));
            $cus->ket=request('keterangan');
            $cus->sales_id=request('sales');
            $cus->total=request('total');
            $cus->perusahaan_id=auth()->user()->perusahaan_id;
            $cus->insert_user=auth()->user()->username;
            $cus->update_user=auth()->user()->username;

            if(request('kd_trans')=="Kredit"){
                $cus->status_pembayaran='Belum Lunas';
                $cus->sisa_pembayaran=request('total');
            }else{
                $cus->status_pembayaran='Lunas';
                $cus->sisa_pembayaran=0;
            }

            $simpan=$cus->save();

            if($simpan){
                if($request->has('kodehit')){
                    $ro=$request->input('kodehit');

                    foreach($ro as $key=>$val){
                        \DB::table('rorder')
                            ->insert(
                                [
                                    'no_order'=>request('kode'),
                                    'kd_brg'=>$val,
                                    'dos'=>$request->input('doshit')[$key],
                                    'pcs'=>$request->input('pcshit')[$key],
                                    'hrg'=>$request->input('jualhit')[$key],
                                    'diskon_persen'=>$request->input('diskon_persen')[$key],
                                    'diskon_rupiah'=>$request->input('diskon_rupiah')[$key],
                                    'jumlah'=>$request->input('jumlahhit')[$key]
                                ]
                            );
                    }
                }
                if($request->has('kodes')){
                    $kodes=request('kodes');

                    foreach($kodes as $key=>$val){

                        // \DB::statement("UPDATE stok SET pcs = pcs-".$request->input('jumlah')[$key]." 
                        //     where kd_brg='".$val."' 
                        //     and lokasi_id='".$request->input('lokasiid')."' 
                        //     and rak_id='".$request->input('rak')[$key]."'");

                        \DB::statement("UPDATE stok SET pcs = pcs-".$request->input('jumlah')[$key]." 
                        where id='".$request->input('idstok')[$key]."'");
                    }
                }

                // if($request->has('listBarang')){
                //     $listbarang=request('listBarang');

                //     foreach($listbarang as $key=>$val){
                //         \DB::table('rorder')
                //             ->insert(
                //                 [
                //                     'no_order'=>request('kode'),
                //                     'kd_brg'=>$val['kd_barang'],
                //                     'dos'=>$val['dos'],
                //                     'pcs'=>$val['pcs'],
                //                     'hrg'=>$val['harga'],
                //                     // 'diskon'=>$val['diskon'],
                //                     'jumlah'=>$val['jumlah']
                //                 ]
                //             );
                //     }
                // }

                $data=array(
                    'success'=>true,
                    'pesan'=>'Data berhasil disimpan',
                    'errors'=>''
                );
            }else{
                $data=array(
                    'success'=>false,
                    'pesan'=>'Data gagal disimpan',
                    'errors'=>''
                );
            }

            
        }

        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Master\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order=Order::with(
            [
                'picking',
                'picking.po.customer',
                'picking.lokasi',
                'picking.perusahaan',
                'detail',
                'perusahaan',
                'sales'
            ]
        )->find($id);

        return $order;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Order  $order
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
     * @param  \App\Master\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Master\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cus=Order::find($id);

        $hapus=$cus->delete();

        if($hapus){
            \DB::table('rorder')
                ->where('no_order',$id)
                ->delete();


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

    public function autonumber_order()
    {
        $sql=Order::select(\DB::Raw("max(no_order) as maxKode"))
            ->first();
        $kodeOrder = $sql->maxKode;
        $noUrut= (int) substr($kodeOrder, 6,6);
        $noUrut++;
        $char = date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }

    public function order_by_id(Request $request,$id){
        $order=Order::select('*',\DB::raw("IF(sisa_pembayaran>0,sisa_pembayaran,total) as total_hutang"))->find($id);

        return $order;
    }
}
