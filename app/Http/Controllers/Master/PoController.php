<?php

namespace App\Http\Controllers\Master;

use App\Models\Po;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $po=Po::with(
            [
                'detail',
                'customer',
                'perusahaan',
                'lokasi'
            ]
        )->where('perusahaan_id',auth()->user()->perusahaan_id);

        if($request->has('q')){
            $po=$po->where('customer_id','like','%'.request('q').'%');
        }

        $po=$po->paginate(25);

        return $po;
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
            'customer'=>'required',
            'lokasi'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi error',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $cus=new Po;
            $cus->no_po=request('kode');
            $cus->customer_id=request('customer');
            $cus->ket=request('keterangan');
            $cus->tgl=date('Y-m-d',strtotime(request('tanggal')));
            $cus->lokasi_id=request('lokasi');
            $cus->perusahaan_id=auth()->user()->perusahaan_id;
            $cus->insert_user=auth()->user()->username;
            $cus->update_user=auth()->user()->username;
            $simpan=$cus->save();

            if($simpan){
                if($request->has('listBarang')){
                    $listbarang=request('listBarang');

                    foreach($listbarang as $key=>$val){
                        \DB::table('rpo')
                            ->insert(
                                [
                                    'no_po'=>request('kode'),
                                    'kd_brg'=>$val['kd_barang'],
                                    'dos'=>$val['dos'],
                                    'pcs'=>$val['pcs'],
                                    'total_pcs'=>$val['total_pcs']
                                ]
                            );
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Master\Po  $po
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $po=Po::with(
            [
                'detail',
                'customer',
                'perusahaan',
                'lokasi'
            ]
        )->find($id);

        return $po;
    }

    public function po_by_id(Request $request,$id)
    {
        $po=Po::with(
            [
                'detail',
                'detail.stok',
                'customer',
                'perusahaan',
                'lokasi'
            ]
        )->find($id);
        
        $list=array();
        $kurangnya=array();

        foreach($po->detail as $row){
            //cek semua stok barang di tabel stok
            $cekStok=\DB::select("SELECT SUM(a.pcs) AS jumlah_stok FROM stok a
                WHERE a.kd_brg='$row->kd'
                AND a.pcs>0
                GROUP BY a.kd_brg");

            if(count($cekStok) > 0){
                $jumlah_stok=$cekStok[0]->jumlah_stok;
                $jumlah_yang_diminta=$row->pivot->total_pcs;
                $default_jumlah_yg_diminta=$row->pivot->total_pcs;

                //ambil di stok berdasarkan barang yang diminta, di order berdasarkan tanggal
                $ambilStok=\DB::select("select a.* from stok a where a.kd_brg='$row->kd' order by a.tgl");

                foreach($ambilStok as $row2){
                    if($jumlah_yang_diminta > 0){
                        if($row2->pcs > $jumlah_yang_diminta){
                            $jumlah_yang_diminta=0;
                            $status='terpenuhi';
                            $dos=$row->pivot->dos;
                            $pcs=$row->pivot->pcs;
                        }else{
                            $status='kurang';
                            $jumlah_yang_diminta=$jumlah_yang_diminta - $row2->pcs;
                            $dos=FLOOR($row2->pcs/$jumlah_yang_diminta);
                            $pcs=FLOOR($row2->pcs % $jumlah_yang_diminta);
                        }
    
                        $list[]=array(
                            'idstok'=>$row2->id,
                            'kd'=>$row->kd,
                            'nm'=>$row->nm,
                            'jual'=>$row->jual,
                            'dos'=>$row->pivot->dos,
                            'pcs'=>$row->pivot->pcs,
                            'pcs_per_dos'=>$row->pcs,
                            'lokasi_id'=>$row2->lokasi_id,
                            'rak_id'=>$row2->rak_id,
                            'jumlah_stok'=>$row2->pcs,
                            'status'=>$status,
                            'realisasi_dosnya'=>$dos,
                            'realisasi_pcsnya'=>$pcs,
                            'default_jml_yg_diminta'=>$default_jumlah_yg_diminta,
                            'jumlah_yg_diminta'=>$jumlah_yang_diminta
                        );
                    }
                }
            }
        }

        $barang_sisa=\DB::select("SELECT semua.id, semua.kd_brg, semua.nm,semua.pcs_per_dos,
                semua.jumlah_stok, semua.yang_diminta, 
                semua.sisa,
                (semua.yang_diminta - semua.jumlah_stok) AS kurangnya,
                FLOOR((semua.yang_diminta - semua.jumlah_stok) / semua.pcs_per_dos) AS dos,
                FLOOR((semua.yang_diminta - semua.jumlah_stok) % semua.pcs_per_dos) AS pcs
                FROM 
                (
                    SELECT a.id, a.kd_brg, b.nm,b.pcs AS pcs_per_dos,
                    SUM(a.pcs) AS jumlah_stok,
                    (SELECT SUM(aa.total_pcs) FROM rpo aa WHERE aa.no_po='$id' AND aa.kd_brg=a.kd_brg GROUP BY a.kd_brg) AS yang_diminta,
                    SUM(a.pcs) - (SELECT SUM(aa.total_pcs) FROM rpo aa WHERE aa.no_po='$id' AND aa.kd_brg=a.kd_brg GROUP BY a.kd_brg ) AS sisa
                    FROM stok a
                    LEFT JOIN brg b ON b.kd=a.kd_brg
                    WHERE a.kd_brg IN (SELECT a.kd_brg FROM rpo a WHERE a.no_po='$id')
                    GROUP BY a.kd_brg
                ) AS semua
                WHERE semua.sisa < 0");

        
        return array(
            'po'=>$po,
            'list'=>$list,
            'kurang'=>$barang_sisa
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Po  $po
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
     * @param  \App\Master\Po  $po
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Master\Po  $po
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cus=Po::find($id);

        $hapus=$cus->delete();

        if($hapus){
            \DB::table('rpo')
                ->where('no_po',$id)
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

    public function autonumber_po()
    {
        $sql=Po::select(\DB::Raw("max(no_po) as maxKode"))
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 10,10);
        $noUrut++;
        $char = "PO-TLG-".date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }

    public function list_po_not_in_picking(Request $request)
    {
        $dimana="";

        if($request->has('status')){
            $status=$request->input('status');

            if($status=="true"){
                $dimana="and no_ref_po is null";
            }

            if($status=="false"){
                $dimana="and no_ref_po is not null";
            }
        }

        if($request->has('customer')){
            $customer=$request->input('customer');
            $dimana.=" and customer_id='".$customer."'";
        }

        $po=\DB::select("select * from po where no_po not in (select no_po from picking) $dimana");

        return $po;
    }
}
