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
            $cus->ket=request('keterangan');
            $cus->sales_id=request('sales');
            $cus->total=request('total');
            $cus->perusahaan_id=auth()->user()->perusahaan_id;
            $cus->insert_user=auth()->user()->username;
            $cus->update_user=auth()->user()->username;

            if(request('kd_trans')=="Kredit"){
                $cus->status_pembayaran='Belum Lunas';
                $cus->sisa_pembayaran=request('total');

                $tambahtanggal=$request->input('customertop');
                $tgl1=date('Y-m-d');
                $sekarang=date('Y-m-d', strtotime('+'.$tambahtanggal.' days', strtotime($tgl1)));

                $cus->tgljt=$sekarang;
            }else{
                $cus->status_pembayaran='Lunas';
                $cus->sisa_pembayaran=0;
            }

            //cek apakah ada program pada tanggal ini atau tidak
            $program=\App\Models\Program::with('detail')
                ->whereRaw("CURDATE() BETWEEN awprriod AND akpriod")
                ->get();

            $simpan=$cus->save();

            if($simpan){
                if($request->has('kodehit')){
                    $ro=$request->input('kodehit');

                    foreach($ro as $key=>$val){
                        $cekB=\App\Models\Barang::find($val);

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

                        $total=($cekB->pcs * $request->input('doshit')[$key]) + $request->input('pcshit')[$key];

                        for($a=0;$a<count($program); $a++){
                            foreach($program[$a]->detail as $key2=>$row2){
                                if($row2->kd == $val && $total >= $row2->pivot->qty){
                                    $point=$row2->pivot->point * round($total / $row2->pivot->qty);
                                    
                                    //simpan ke customer point
                                    \DB::table('customer_point')
                                        ->insert(
                                            [
                                                'customer_id'=>$request->input('kd_customer'),
                                                'program_id'=>$program[$a]->nmr,
                                                'no_order'=>$request->input('kode'),
                                                'kd_barang'=>$val,
                                                'point'=>$point,
                                                'created_at'=>date('Y-m-d H:i:s'),
                                                'updated_at'=>date('Y-m-d H:i:s')
                                            ]
                                        );
                                }
                            }
                        }
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

    public function info_barang_by_order(Request $request)
    {
        $rules=[
            'noorder'=>'required',
            'kode'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi error',
                'data'=>array()
            );
        }else{
            $noorder=$request->input('noorder');
            $kode=$request->input('kode');

            $lis=\DB::select("SELECT a.no_order, b.kd_brg, b.dos, 
                b.pcs,c.nm, b.hrg, b.jumlah, b.diskon_persen, b.diskon_rupiah, 
                c.pcs as pcs_barang,
                (b.dos * c.pcs + b.pcs) as total_pcs,
                if(b.dos * c.pcs + b.pcs > c.pcs,'lebih','kurang') as statusnya
                FROM orders a
                LEFT JOIN rorder b ON b.no_order=a.no_order
                LEFT JOIN brg c ON c.kd=b.kd_brg
                WHERE a.no_order='$noorder'
                AND b.kd_brg='$kode'");

                $data=array(
                    'success'=>true,
                    'pesan'=>'Data berhasil di load',
                    'data'=>$lis
                );
        }

        return $data;
        
    }

    public function list_order_client(Request $request)
    {
        $cus=$request->input('customer');

        if($request->has('nota')){
            $nota=$request->input('nota');

            if($nota==true){
                $lis=\DB::select("SELECT a.no_order, b.kd_picking, c.customer_id 
                    FROM orders a
                    LEFT JOIN picking b ON b.kd_picking=a.kd_picking
                    LEFT JOIN po c ON c.no_po=b.no_po
                    WHERE c.customer_id='$cus'
                    AND a.no_order not in (select distinct no_order from retur)");
            }else if($nota==false){
                $lis=\DB::select("SELECT a.no_order, b.kd_picking, c.customer_id 
                    FROM orders a
                    LEFT JOIN picking b ON b.kd_picking=a.kd_picking
                    LEFT JOIN po c ON c.no_po=b.no_po
                    WHERE c.customer_id='$cus'");
            }
        }else{
            $lis=\DB::select("SELECT a.no_order, b.kd_picking, c.customer_id
                FROM orders a
                LEFT JOIN picking b ON b.kd_picking=a.kd_picking
                LEFT JOIN po c ON c.no_po=b.no_po
                WHERE c.customer_id='$cus'");
        }
        

        return $lis;
    }

    public function detail_order_by_id($id)
    {
        $order=\DB::select("SELECT a.no_order, b.kd_picking, c.customer_id, e.nm, c.lokasi_id, d.nm FROM orders a
            LEFT JOIN picking b ON b.kd_picking=a.kd_picking
            LEFT JOIN po c ON c.no_po=b.no_po
            LEFT JOIN lokasi d ON d.id=c.lokasi_id
            LEFT JOIN customer e ON e.kd=c.customer_id
            WHERE a.no_order='$id'");

        $detail=\DB::select("SELECT a.*, b.nm, b.pcs as pcs_barang FROM rorder a
            LEFT JOIN brg b ON b.kd=a.kd_brg
            WHERE a.no_order='$id'");

        return array(
            'order'=>$order,
            'detail'=>$detail
        );
    }

    public function order_jatuh_tempo(Request $request)
    {
        // $lis=\DB::select("select a.*, d.nm,DATEDIFF(a.tgljt,CURDATE()) as minus_hari
        //     from orders a
        //     left join picking b on b.kd_picking=a.kd_picking
        //     left join po c on c.no_po=b.no_po 
        //     left join customer d on d.kd=c.customer_id
        //     where a.kd_trans='Kredit'
        //     AND CASE
        //         WHEN DATEDIFF(a.tgljt,CURDATE())>0 THEN 'BELUM'
        //         ELSE 'TELAT'
        //     END ='TELAT'");

        $lis=\App\Models\Po::where('status_konfirmasi','Please Confirm')
            ->with(
                [
                    'customer','detail',
                    'lokasi','perusahaan'
                ]
            )
            ->paginate(25);

        return $lis;
    }

    public function update_status_order(Request $request,$id)
    {
        $rules=[
            'status'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $po=\App\Models\Po::find($id);
            
            $status=$request->input('status');

            if($status=="Accept"){
                $po->status_konfirmasi=$status;
                $po->save();
                $data=array(
                    'success'=>true,
                    'pesan'=>'Data Berhasil di accept'
                );
            }else if($status=="Refuse"){
                $hapus=$po->delete();
                $data=array(
                    'success'=>true,
                    'pesan'=>'Data Berhasil di refuse'
                );
            }
        }

        return $data;
    }

    public function sisa_hutang_customer(Request $request,$id)
    {
        $lis=\DB::select("select SUM(a.sisa_pembayaran) AS sisa
            from orders a
            left join picking b on b.kd_picking=a.kd_picking
            left join po c on c.no_po=b.no_po 
            left join customer d on d.kd=c.customer_id
            where a.kd_trans='Kredit'
            and c.customer_id='$id'
            GROUP BY c.customer_id");
        
        $sisa=0;
        $plafon=0;

        $customer=\App\Models\Customer::find($id);

        $plafon=$customer->plafon;

        foreach($lis as $row){
            $sisa=$row->sisa;
        }

        if($sisa>$plafon){
            $boleh="Y";
        }else{
            $boleh="N";
        }

        return array(
            'sisa'=>$sisa,
            'plafon'=>$plafon,
            'boleh'=>$boleh
        );
    }

    public function list_order_lunas(Request $request)
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
        )->where('status_pembayaran','Lunas');

        if($request->has('q')){
            $order=$order->where('customer_id','like','%'.request('q').'%');
        }

        $order=$order->paginate(25);

        return $order;
    }

    public function list_order_hutang(Request $request)
    {
        // SELECT a.no_order, c.customer_id, d.nm, d.nm_toko 
        // FROM orders a
        // LEFT JOIN picking b ON b.kd_picking=a.kd_picking
        // LEFT JOIN po c ON c.no_po=b.no_po
        // LEFT JOIN customer d ON d.kd=c.customer_id

        $order=\DB::Table('orders as a')
            ->leftJoin('picking as b','b.kd_picking','=','a.kd_picking')
            ->leftJoin('po as c','c.no_po','=','b.no_po')
            ->leftJoin('customer as d','d.kd','=','c.customer_id')
            ->select('a.no_order','c.customer_id','d.nm','d.nm_toko','d.saldo');

        if($request->has('q')){
            $order=$order->where('a.no_order','like','%'.$request->input('q').'%')
                ->orWhere('c.customer_id','like','%'.$request->input('q').'%');
        }

        $order=$order->get();

        return $order;
    }

    public function list_order_by_id(Request $request, $id)
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
        )->find($id);

        return $order;
    }
}
