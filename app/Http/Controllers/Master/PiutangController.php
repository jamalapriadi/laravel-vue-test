<?php

namespace App\Http\Controllers\Master;

use App\Models\Piutang;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PiutangController extends Controller
{
    public function index(Request $request)
    {
        $sql=\DB::select("SELECT 
        b.no_po,
        c.customer_id,
        a.no_order,
        a.kd_picking,
        a.kd_trans,
        a.tgl,
        a.tgljt,
        a.ket,
        a.sales_id,
        a.perusahaan_id,
        a.total AS total_hutang,
        IFNULL((
            SELECT SUM(aa.total_bayar) FROM piutang aa
            WHERE aa.customer_id=c.customer_id
            GROUP BY aa.customer_id
        ),0) AS total_dibayar
        FROM orders a
        LEFT JOIN picking b ON b.kd_picking=a.kd_picking
        LEFT JOIN po c ON c.no_po=b.no_po
        WHERE a.kd_trans='Kredit'");


        return $sql;
    }

    public function store(Request $request)
    {
        // return $request->all();
        $rules=[
            'kode'=>'required',
            'customer'=>'required',
            // 'jenis'=>'required',
            'tanggal'=>'required',
            'total_piutang'=>'required',
            'nominal'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $kode=$this->autonumber_piutang();

            $p=new Piutang;
            $p->no_piutang=$kode;
            $p->tgl_pembayaran=date('Y-m-d',strtotime(request('tanggal')));
            $p->customer_id=request('customer');
            // $p->jenis=request('jenis');
            $p->total_bayar=request('nominal');
            $p->keterangan=request('keterangan');
            $p->insert_user=auth()->user()->username;
            $p->update_user=auth()->user()->username;

            $simpan=$p->save();

            $total_piutang=request('total_piutang');
            $nominal=request('nominal'); //nominal ada jumlah nominal yang dibayar
            $total=request('total'); //total adalah jumlah total dari detail yang dibayar

            if($simpan){
                if($request->has('detail')){
                    $detail=request('detail');
                    $total=0;

                    foreach($detail as $key=>$val){
                        \DB::table('rpiutang')
                            ->insert(
                                [
                                    'no_piutang'=>$request->input('kode'),
                                    'jns_pembayaran'=>$val['jns_pembayaran'],
                                    'bank'=>$val['bank'],
                                    'no_cek_bg'=>$val['no_cek_bg'],
                                    'no_order'=>$val['no_order'],
                                    'tgl_jt_transfer'=>date('Y-m-d',strtotime($val['tgl_jt'])),
                                    'nominal'=>$val['nominal'],
                                    'keterangan'=>$val['keterangan'],
                                    'created_at'=>date('Y-m-d'),
                                    'updated_at'=>date('Y-m-d')
                                ]
                            );
                            
                        $total+=$val['nominal'];

                        $order=\App\Models\Order::find($val['no_order']);
                        if($val['tagihan'] > $val['nominal']){
                            $order->status_pembayaran='Belum Lunas';
                            $order->sisa_pembayaran= $val['tagihan'] - $val['nominal'];
                        }else{
                            $order->status_pembayaran='Lunas';
                            $order->sisa_pembayaran = $val['tagihan'] - $val['nominal'];
                        }
                        $order->save();

                    }

                    if($nominal > $total){
                        $cus=\App\Models\Customer::find(request('customer'));
                        $cus->saldo=(int)$cus->saldo + $nominal - $total;
                        $cus->save();
                    }
                }
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

    public function show(Request $request,$id)
    {
        $p=Piutang::find($id);

        return $p;
    }

    public function update(Request $request,$id)
    {
        $rules=[
            'no_piutang'=>'required',
            'tgl'=>'required',
            'no_order'=>'required',
            'total'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $p=Piutang::find($id);
            $p->no_piutang=request('no_piutang');
            $p->tgl_pembayaran=date('Y-m-d',strtotime(request('tgl')));
            $p->no_order=request('no_order');
            $p->total_bayar=request('total');
            $p->insert_user=auth()->user()->username;
            $p->update_user=auth()->user()->username;

            $simpan=$p->save();

            if($simpan){
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

    public function destory(Request $request,$id)
    {
        $p=Piutang::find($id);

        $hapus=$p->delete();

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

    public function piutang_by_order($id){
        $order=Order::with(
            [
                'picking',
                'picking.po.customer',
                'picking.lokasi',
                'picking.perusahaan',
                'detail',
                'perusahaan',
                'sales',
                'piutang'
            ]
        )->find($id);

        return $order;
    }

    public function pilih_order_di_new_po($id){
        $order=Order::with(
            [
                'picking',
                'picking.po.customer',
                'picking.lokasi',
                'picking.perusahaan',
                'detail',
                'perusahaan',
                'sales',
                'piutang'
            ]
        )->find($id);

        $piutang=\DB::select("SELECT c.customer_id,d.nm, d.nm_toko,
                a.no_order,
                a.kd_picking,
                a.kd_trans,
                a.tgl,
                a.tgljt,
                a.ket,
                a.sales_id,
                a.perusahaan_id,
                a.total AS total_hutang,
                ifnull((
                    SELECT SUM(aa.total_bayar) FROM piutang aa
                    WHERE aa.no_order=a.no_order
                    GROUP BY aa.no_order
                ),0) AS sudah_dibayar,
                a.total - ifnull((
                    SELECT SUM(aa.total_bayar) FROM piutang aa
                    WHERE aa.no_order=a.no_order
                    GROUP BY aa.no_order
                ),0) AS sisa_hutang
                FROM orders a
                LEFT JOIN picking b ON b.kd_picking=a.kd_picking
                LEFT JOIN po c ON c.no_po=b.no_po
                LEFT JOIN customer d ON d.kd=c.customer_id
                WHERE a.kd_trans='Kredit'
                AND a.no_order='$id'
                AND a.total > (
                    SELECT SUM(aa.total_bayar) FROM piutang aa
                    WHERE aa.no_order=a.no_order
                    GROUP BY aa.no_order
                )");

        return array(
            'order'=>$order,
            'piutang'=>$piutang
        );
    }

    public function autonumber_piutang(){
        $perusahaan=auth()->user()->perusahaan->nama;

        $sql=Piutang::select(\DB::Raw("max(no_piutang) as maxKode"))
            ->where('no_piutang','like','PTG-'.$perusahaan.'%')
            ->first();
        $kodeOrder = $sql->maxKode;
        $noUrut= (int) substr($kodeOrder, 11,11);
        $noUrut++;
        $char = "PTG-".$perusahaan."-".date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }

    public function order_belum_lunas(Request $request){
        $rules=[
            'customer'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'validasi errors',
                'errors'=>$validasi->errors()->all(),
                'data'=>array()
            );
        }else{
            $cus=request('customer');

            // $lis=\DB::select("SELECT c.customer_id,d.nm, d.nm_toko,
            //     a.no_order,
            //     a.kd_picking,
            //     a.kd_trans,
            //     a.tgl,
            //     a.tgljt,
            //     a.ket,
            //     a.sales_id,
            //     a.perusahaan_id,
            //     a.total AS total_hutang,
            //     IFNULL((
            //         SELECT SUM(aa.total_bayar) FROM piutang aa
            //         WHERE aa.customer_id=c.customer_id
            //         GROUP BY aa.customer_id
            //     ),0) AS sudah_dibayar,
            //     a.total - IFNULL((
            //         SELECT SUM(aa.total_bayar) FROM piutang aa
            //         WHERE aa.customer_id=c.customer_id
            //         GROUP BY aa.customer_id
            //     ),0) AS sisa_hutang
            //     FROM orders a
            //     LEFT JOIN picking b ON b.kd_picking=a.kd_picking
            //     LEFT JOIN po c ON c.no_po=b.no_po
            //     LEFT JOIN customer d ON d.kd=c.customer_id
            //     WHERE a.kd_trans='Kredit'
            //     AND c.customer_id='$cus'
            //     AND a.total > IFNULL((
            //         SELECT SUM(aa.total_bayar) FROM piutang aa
            //         WHERE aa.customer_id=c.customer_id
            //         GROUP BY aa.customer_id
            //     ),0)");

            $lis=\DB::select("SELECT c.customer_id,d.nm, d.nm_toko,
                        a.no_order,
                        a.kd_picking,
                        a.kd_trans,
                        a.tgl,
                        a.tgljt,
                        a.ket,
                        a.sales_id,
                        a.perusahaan_id,
                        a.total,
                        a.status_pembayaran,
                        a.total - IFNULL(a.sisa_pembayaran,0) as sudah_dibayar,
                        IFNULL(a.sisa_pembayaran,a.total) AS total_hutang
                        FROM orders a
                        LEFT JOIN picking b ON b.kd_picking=a.kd_picking
                        LEFT JOIN po c ON c.no_po=b.no_po
                        LEFT JOIN customer d ON d.kd=c.customer_id
                        WHERE a.kd_trans != 'Lunas'
                        AND a.status_pembayaran='Belum Lunas'
                        AND c.customer_id='$cus'");

            $data=array(
                'success'=>true,
                'pesan'=>"data berhasil diload",
                'errors'=>array(),
                'data'=>$lis
            );
        }

        return $data;
    }

    public function tampil_hutang_customer(Request $request){
        $rules=[
            'customer'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'validasi errors',
                'errors'=>$validasi->errors()->all(),
                'data'=>array()
            );
        }else{
            $cus=request('customer');

            $lis=\DB::select("SELECT SUM(semua.sisa_hutang) AS total_hutang
            FROM 
            (
                SELECT c.customer_id,d.nm, d.nm_toko,
                 a.no_order,
                 a.kd_picking,
                 a.kd_trans,
                 a.tgl,
                 a.tgljt,
                 a.ket,
                 a.sales_id,
                 a.perusahaan_id,
                 a.total AS total_hutang,
                 IFNULL((
                     SELECT SUM(aa.total_bayar) FROM piutang aa
                     WHERE aa.customer_id=c.customer_id
                     GROUP BY aa.customer_id
                 ),0) AS sudah_dibayar,
                 a.total - IFNULL((
                     SELECT SUM(aa.total_bayar) FROM piutang aa
                     WHERE aa.customer_id=c.customer_id
                     GROUP BY aa.customer_id
                 ),0) AS sisa_hutang
                 FROM orders a
                 LEFT JOIN picking b ON b.kd_picking=a.kd_picking
                 LEFT JOIN po c ON c.no_po=b.no_po
                 LEFT JOIN customer d ON d.kd=c.customer_id
                 WHERE a.kd_trans != 'Lunas'
                 AND c.customer_id='$cus'
                 AND a.total > IFNULL((
                     SELECT SUM(aa.total_bayar) FROM piutang aa
                     WHERE aa.customer_id=c.customer_id
                     GROUP BY aa.customer_id
                 ),0)
            )AS semua
            GROUP BY semua.customer_id");

            $data=array(
                'success'=>true,
                'pesan'=>"data berhasil diload",
                'errors'=>array(),
                'data'=>$lis
            );
        }

        return $data;
    }
}