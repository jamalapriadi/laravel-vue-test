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
        )->where('perusahaan_id',auth()->user()->perusahaan_id);

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
        // return $request->all();
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
            //validasi dulu antara yang di picking dan yang diinput
            $kodehit=$request->input('kodehit');
            $kd_picking=$request->input('kd_picking');
            $data=array();
            $listnya=array();

            foreach($kodehit as $key=>$val){
                $listnya[]=$val;

                $data[]=array(
                    'kd_barang'=>$val,
                    'jumlah'=>$request->input('jumlahhit')[$key]
                );
            }
            
            $unique_list=array_unique($listnya);

            $hasil=array();

            foreach($unique_list as $key=>$val){
                $jumlah=0;
                for($a=0; $a<count($data); $a++){
                    if($data[$a]['kd_barang'] == $val){
                        $jumlah+=$data[$a]['jumlah'];
                    }
                }

                $cekJumlahPicking=\DB::table('rpicking')
                    ->where('kd_picking',$kd_picking)
                    ->where('kd_brg',$val)
                    ->get();

                $total_picking=0;
                foreach($cekJumlahPicking as $key2=>$row){
                    $brg=\App\Models\Barang::find($row->kd_brg);

                    $total_picking+=($brg->pcs * $row->dos) + $row->pcs;
                }

                if($total_picking == $jumlah){
                    
                }else{
                    $hasil[]=array(
                        'kd_barang'=>$val,
                        'total_picking'=>$total_picking,
                        'jumlah'=>$jumlah,
                        'status'=>'ok'
                    );
                }
            }

            if(count($hasil) > 0){
                return array(
                    'success'=>false,
                    'pesan'=>'Total Barang di picking dan yang diinput tidak sesuai',
                    'lis'=>$hasil
                );
            }


            $kode=$this->autonumber_order();
            $lokasi=$request->input('lokasiid');

            $cus=new Order;
            $cus->no_order=$kode;
            $cus->kd_picking=request('kd_picking');
            $cus->kd_trans=request('kd_trans');
            $cus->tgl=date('Y-m-d',strtotime(request('tanggal')));
            $cus->ket=request('keterangan');
            $cus->sales_id=request('sales');
            $cus->diskon_rupiah=request('diskon_tambahan');
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
                    $status=$request->input('status');

                    foreach($ro as $key=>$val){
                        if($status[$key] == 'old'){
                            $cekB=\App\Models\Barang::find($val);

                            \DB::table('rorder')
                                ->insert(
                                    [
                                        'no_order'=>$kode,
                                        'kd_brg'=>$val,
                                        'dos'=>$request->input('doshit')[$key],
                                        'pcs'=>$request->input('pcshit')[$key],
                                        'hrg'=>$request->input('jualhit')[$key],
                                        'diskon_persen'=>$request->input('diskon_persen')[$key],
                                        'diskon_persen_2'=>$request->input('diskon_rupiah')[$key],
                                        'subtotal'=>$request->input('subtotal')[$key],
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
                        }else{
                            //cari stok barang  untuk barang yang tidak mempunyai stok id
                            $newstok=\DB::select("select sum(pcs) as jml_stok from stok where kd_brg='".$val."' AND lokasi_id='".$lokasi."'");
                            $newliststok=\DB::select("select * from stok  where kd_brg='".$val."' and pcs > 0  AND lokasi_id='".$lokasi."'");

                            $final_total_diinput=(int)$request->input('jumlahhit')[$key];

                            if(count($newstok) > 0){
                                $stok_all = $newstok[0]->jml_stok;

                                //bandingkan dulu qty dengan stok yang ada 
                                if($final_total_diinput <= $stok_all){

                                    //lakukan perulangan pada setiap list stok barang
                                    foreach($newliststok as $new){
                                        $lstok = $new->pcs;

                                        //selama qty > 0 (belum habis) artinya stok pada list akan di eksekusi
                                        if($final_total_diinput > 0){
                                            $tmp=$final_total_diinput;

                                            $final_total_diinput = $final_total_diinput-$lstok;

                                            if($final_total_diinput > 0){
                                                $stok_update = 0;
                                            }else{
                                                $stok_update = $final_total_diinput - $tmp;
                                            }

                                            $cekNewBarang=\App\Models\Barang::find($val);

                                            if($final_total_diinput > 0){
                                                $dosNewBarang=FLOOR($cekNewBarang->pcs/$final_total_diinput);
                                                $pcsNewBarang=FLOOR($cekNewBarang->pcs % $final_total_diinput); 
                                            }else{
                                                $dosNewBarang=FLOOR($tmp/$cekNewBarang->pcs);
                                                $pcsNewBarang=FLOOR($tmp % $cekNewBarang->pcs); 
                                            }

                                            $newTotal=($dosNewBarang * $cekNewBarang->pcs)+$pcsNewBarang;

                                            \DB::table('rpicking')
                                                ->insert(
                                                    [
                                                        'kd_picking'=>$request->input('kd_picking'),
                                                        'kd_brg'=>$val,
                                                        'kd_rak'=>$new->rak_id,
                                                        'pdos'=>$request->input('doshit')[$key],
                                                        'ppcs'=>$request->input('pcshit')[$key],
                                                        'dos'=>$request->input('doshit')[$key],
                                                        'pcs'=>$request->input('pcshit')[$key],
                                                        'stok_id'=>$new->id
                                                    ]
                                                );

                                            \DB::statement("UPDATE stok SET pcs = pcs-".$newTotal." 
                                                where id='".$new->id."'");
                                        }
                                    }
                                }
                            }

                            \DB::table('rorder')
                                ->insert(
                                    [
                                        'no_order'=>$kode,
                                        'kd_brg'=>$val,
                                        'dos'=>$request->input('doshit')[$key],
                                        'pcs'=>$request->input('pcshit')[$key],
                                        'hrg'=>$request->input('jualhit')[$key],
                                        'diskon_persen'=>$request->input('diskon_persen')[$key],
                                        'diskon_persen_2'=>$request->input('diskon_rupiah')[$key],
                                        'subtotal'=>$request->input('subtotal')[$key],
                                        'jumlah'=>$request->input('jumlahhit')[$key]
                                    ]
                                );
                            
                        }
                    }
                }

                /* update stok
                if($request->has('kodes')){
                    $kodes=request('kodes');

                    foreach($kodes as $key=>$val){
                        \DB::statement("UPDATE stok SET pcs = pcs-".$request->input('jumlah')[$key]." 
                        where id='".$request->input('idstok')[$key]."'");
                    }
                }
                */

                // if($request->has('listBarang')){
                //     $listbarang=request('listBarang');

                //     foreach($listbarang as $key=>$val){
                //         \DB::table('rorder')
                //             ->insert(
                //                 [
                //                     'no_order'=>$kode,
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

                $nota=Order::with(
                    [
                        'picking',
                        'picking.po.customer',
                        'picking.lokasi',
                        'picking.perusahaan',
                        'detail',
                        'perusahaan',
                        'sales'
                    ]
                )->find($kode);

                $keterangan=\App\Models\Ket::where('perusahaan_id',auth()->user()->perusahaan_id)->first();

                $data=array(
                    'success'=>true,
                    'pesan'=>'Data berhasil disimpan',
                    'errors'=>'',
                    'nota'=>$nota,
                    'keterangan'=>$keterangan
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

    public function cancel_order(Request $request)
    {
        $rules=[
            'kd_picking'=>'required',
            'detail'=>'required',
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi error',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $picking=\App\Models\Picking::find(request('kd_picking'));
            $hapus=$picking->delete();

            if($hapus){
                \DB::table('rpicking')
                    ->where('kd_picking',request('kd_picking'))
                    ->delete();


                if($request->has('detail')){
                    $detail=$request->input('detail');

                    foreach($detail as $key=>$val){
                        $newStok=new \App\Models\Stok;
                        $newStok->kd_brg=$val['kd'];
                        $newStok->lokasi_id=$request->input('po')['lokasi_id'];
                        $newStok->rak_id=$val['pivot']['kd_rak'];
                        $newStok->tgl=date('Y-m-d');
                        $newStok->pcs=$val['total'];
                        $newStok->save();
                    }
                }

                $data=array(
                    'success'=>true,
                    'pesan'=>'Stok berhasil dikembalikan',
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
        $perusahaan=auth()->user()->perusahaan->nama;

        $sql=Order::select(\DB::Raw("max(no_order) as maxKode"))
            ->where('no_order','like','ODR-'.$perusahaan.'%')
            ->first();
        $kodeOrder = $sql->maxKode;
        $noUrut= (int) substr($kodeOrder, 11,11);
        $noUrut++;
        $char = "ODR-".$perusahaan."-".date('y')."-";
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
        $status="";

        if($request->has('lunas')){
            $lunas=$request->input('lunas');

            if($lunas=="true"){
                $status=" AND a.status_pembayaran='Lunas'";
            }else{
                $status=" AND a.status_pembayaran!='Lunas'";
            }
        }

        if($request->has('nota')){
            $nota=$request->input('nota');

            if($nota==true){
                $lis=\DB::select("SELECT a.no_order, b.kd_picking, c.customer_id, a.status_pembayaran 
                    FROM orders a
                    LEFT JOIN picking b ON b.kd_picking=a.kd_picking
                    LEFT JOIN po c ON c.no_po=b.no_po
                    WHERE c.customer_id='$cus'
                    $status
                    AND a.no_order not in (select distinct no_order from retur)");
            }else if($nota==false){
                $lis=\DB::select("SELECT a.no_order, b.kd_picking, c.customer_id , a.status_pembayaran
                    FROM orders a
                    LEFT JOIN picking b ON b.kd_picking=a.kd_picking
                    LEFT JOIN po c ON c.no_po=b.no_po
                    WHERE c.customer_id='$cus' $status");
            }
        }else{
            $lis=\DB::select("SELECT a.no_order, b.kd_picking, c.customer_id, a.status_pembayaran
                FROM orders a
                LEFT JOIN picking b ON b.kd_picking=a.kd_picking
                LEFT JOIN po c ON c.no_po=b.no_po
                WHERE c.customer_id='$cus' $status");
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

    public function print_order($id)
    {
        $data['user']=\App\User::with('perusahaan')->find(auth()->user()->id);

        $data['nota']=Order::with(
            [
                'picking',
                'picking.po.customer',
                'picking.lokasi',
                'picking.perusahaan',
                'detail',
                'perusahaan',
                'perusahaan.ket',
                'sales'
            ]
        )->find($id);

        // $data['user']=$user;
        // $data['nota']=$nota;
        // $data=array();
        
        // $customPaper = array(0,0,567.00,583.80);
        $customPaper = array(0,0,567.00,683.80);

        $pdf = \PDF::loadView('print.nota', $data)
            ->setOptions(
                [
                    'isHtml5ParserEnabled'=>true
                ]
            )
            // ->setPaper($customPaper, 'landscape');
            ->setPaper('a5', 'landscape');
        return $pdf->stream();

        return view('print.nota')
                ->with('nota',$nota)
                ->with('user',$user);
    }
}
