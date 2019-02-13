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
            'kode'=>'required',
            'tanggal'=>'required',
            'kd_picking'=>'required'
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
            $cus->ket=request('keterangan');
            $cus->total=request('total');
            $cus->perusahaan_id=auth()->user()->perusahaan_id;
            $cus->insert_user=auth()->user()->username;
            $cus->update_user=auth()->user()->username;
            $simpan=$cus->save();

            if($simpan){
                if($request->has('kodes')){
                    $kodes=request('kodes');

                    foreach($kodes as $key=>$val){
                        \DB::table('rorder')
                            ->insert(
                                [
                                    'no_order'=>request('kode'),
                                    'kd_brg'=>$val,
                                    'dos'=>$request->input('dos')[$key],
                                    'pcs'=>$request->input('pcs')[$key],
                                    'hrg'=>$request->input('jual')[$key],
                                    // 'diskon'=>$val['diskon'],
                                    'jumlah'=>$request->input('jumlah')[$key]
                                ]
                            );
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
                'picking.sales',
                'picking.lokasi',
                'picking.perusahaan',
                'detail',
                'perusahaan',
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
}
