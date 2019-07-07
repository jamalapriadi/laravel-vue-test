<?php

namespace App\Http\Controllers\Master;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class LaporanController extends Controller
{
    public function laporan_penjualan(Request $request)
    {
        $rules=[
            'mulai'=>'required',
            'selesai'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi Error',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $mulai=date('Y-m-d',strtotime(request('mulai')));
            $selesai=date('Y-m-d',strtotime(request('selesai')));

            $model=Order::with(
                [
                    'picking',
                    'picking.po.customer',
                ]
            )->select(
                [
                    DB::raw("IFNULL(no_order,'TOTAL') as no_order"),
                    'kd_picking',
                    'kd_trans',
                    'sales_id',
                    'status_pembayaran',
                    \DB::Raw("SUM(total) as total"),
                    \DB::Raw("SUM(sisa_pembayaran) as sisa_pembayaran")
                ]
            )
            ->whereBetween('tgl',[$mulai,$selesai])
            ->groupBy(DB::raw('no_order WITH ROLLUP'));

            if($sales=request('sales')){
                $model=$model->where('sales_id',$sales);
            }

            $model=$model->get();

            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil diload',
                'mulai'=>date('d-M-Y',strtotime(request('mulai'))),
                'selesai'=>date('d-M-Y',strtotime(request('selesai'))),
                'lis'=>$model,
                'errors'=>array()
            );
        }

        return $data;
    }

    public function laporan_penjualan_per_sales(Request $request)
    {
        $rules=[
            'mulai'=>'required',
            'selesai'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi error',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $mulai=date('Y-m-d',strtotime(request('mulai')));
            $selesai=date('Y-m-d',strtotime(request('selesai')));

            $q="";
            if($request->has('sales') && $request->input('sales')!=null){
                $sales=request('sales');

                $q.=" AND b.sales_id=$sales";
            }

            if($request->has('kelompok') && $request->input('kelompok')!=null){
                $kelompok = request('kelompok');

                $q.=" AND c.kelompok_id = $kelompok";
            }

            $lis=DB::select("SELECT ifnull(ts.id,'SUBTOTAL') AS id, ifnull(ts.no_order,'TOTAL') AS no_order, ts.nama_sales, ts.kd_brg, ts.nama_barang, ts.nama_kelompok,
                SUM(ts.jumlah) AS jumlah,
                SUM(ts.total_harga) AS total
                FROM
                (
                    SELECT a.id,a.no_order,d.nm AS nama_sales, a.kd_brg,c.nm AS nama_barang, e.nm AS nama_kelompok, a.jumlah, a.hrg, a.jumlah * a.hrg AS total_harga 
                    FROM rorder a
                    LEFT JOIN orders b ON b.no_order=a.no_order
                    LEFT JOIN brg c ON c.kd=a.kd_brg
                    LEFT JOIN sales d ON d.id=b.sales_id
                    LEFT JOIN klmpk e ON e.id=c.kelompok_id
                    WHERE b.tgl BETWEEN '$mulai' AND '$selesai'
                    $q
                ) AS ts
                GROUP BY ts.no_order, ts.id
                WITH ROLLUP ");

            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil diload',
                'mulai'=>date('d-M-Y',strtotime(request('mulai'))),
                'selesai'=>date('d-M-Y',strtotime(request('selesai'))),
                'lis'=>$lis,
                'errors'=>array()
            );
        }
        
        return $data;
    }

    public function laporan_stok(Request $request)
    {
        $q="";

        if($request->has('lokasi') && $request->input('lokasi')!=null){
            $lokasi=$request->input('lokasi');
            $q.="where a.lokasi_id=$lokasi";
        }else{
            $lisLokasi=\App\Models\Lokasi::select('id')->get();

            foreach($lisLokasi as $key=>$row){
                if($key==0){
                    $q.="where a.lokasi_id='$row->id'";
                }else{
                    $q.=" or a.lokasi_id='$row->id'";
                }
            }
        }

        if($request->has('kelompok') && $request->input('kelompok')!=null){
            $kelompok=$request->input('kelompok');

            $q.=" and c.kelompok_id=$kelompok";
        }

        $lis=DB::select("SELECT IFNULL(a.lokasi_id,'TOTAL') AS lokasi_id,b.lokasi,  IFNULL(a.kd_brg,'SUBTOTAL') AS kode_barang,c.nm AS nama_barang, SUM(a.pcs) AS jumlah_stok
            FROM stok a
            LEFT JOIN lokasi b ON b.id=a.lokasi_id
            LEFT JOIN brg c ON c.kd=a.kd_brg
            $q
            GROUP BY a.lokasi_id, a.kd_brg");

        return array(
            'success'=>true,
            'tanggal_cetak'=>date('d-M-Y H:i:s'),
            'lis'=>$lis
        );
    }
}