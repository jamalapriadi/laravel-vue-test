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
        $picking=\App\Models\Picking::where('status_terpenuhi','Y')->pluck('no_po');

        $po=Po::with(
            [
                'detail',
                'customer',
                'perusahaan',
                'lokasi'
            ]
        )->where('perusahaan_id',auth()->user()->perusahaan_id)
        ->whereNotIn('no_po',$picking);

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
            
            
            //cek apakah dia punya order yang sudah overdate atau belum
            $customer=request('customer');

            $lis=\DB::select("select a.*, d.nm,DATEDIFF(a.tgljt,CURDATE()) as minus_hari
                from orders a
                left join picking b on b.kd_picking=a.kd_picking
                left join po c on c.no_po=b.no_po 
                left join customer d on d.kd=c.customer_id
                where a.kd_trans='Kredit'
                and c.customer_id='$customer'
                AND CASE
                    WHEN DATEDIFF(a.tgljt,CURDATE())>0 THEN 'BELUM'
                    ELSE 'TELAT'
                END ='TELAT'");

            if(count($lis)>0){
                $adahutang="Y";
                $cus->status_konfirmasi="Please Confirm";
                $cus->info="Customer ini mempunyai hutang yang sudah melewati batas jatuh tempo";
            }else{
                if($request->has('statuskonfirmasi')){
                    $statuskonfirmasi=$request->input('statuskonfirmasi');

                    if($statuskonfirmasi=="Accept"){
                        $adahutang="N";
                        $cus->status_konfirmasi=$request->input('statuskonfirmasi');
                    }else if($statuskonfirmasi=="Please Confirm"){
                        $adahutang="X";
                        $cus->status_konfirmasi=$request->input('statuskonfirmasi');
                        $cus->info="Jumlah plafon melebihi jumlah harga yang di order";
                    }
                    
                }else{
                    $adahutang="N";
                }
            }


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

                $nota=$po=Po::with(
                    [
                        'detail',
                        'customer',
                        'perusahaan',
                        'lokasi'
                    ]
                )->find($cus->no_po);

                //cek stok barangnya
                $list=array();
                foreach($nota->detail as $detail){
                    $barang=\App\Models\Barang::find($detail->kd);            

                    $pcs=$detail->pivot->pcs;
                    $dos=$detail->pivot->dos;
                    $kurangnya=$barang->pcs*$dos+$pcs;
                    $sudahdiambil=$barang->pcs*$dos+$pcs;

                    $stokbarang=\DB::table('stok')
                        ->leftJoin('rak','rak.kd','=','stok.rak_id')
                        ->where('kd_brg',$detail->kd)
                        ->select('rak.nm as nama_rak','stok.*')
                        ->get();

                    $rak=array();
                    $sisa=0;

                    foreach($stokbarang as $row){
                        if($kurangnya > 0){
                            // if($row->pcs > $kurangnya){
                            //     $diambil = $row->pcs;
                            // }else{
                            //     $diambil = $row->pcs;
                            // }
                            
                            //jika stok barang lebih dari jumlah kurangnya, maka diambil semua kurang nya
                            if($kurangnya > $row->pcs){
                                $diambil=$row->pcs;
                            }else{
                                //jika stok barang kurang dari  barang yang ingin diambil
                                if($row->pcs > $kurangnya){
                                    $diambil=$kurangnya;
                                }else{
                                    $diambil=$row->pcs;
                                }
                            }
                            

                            $rak[]=array(
                                'rak'=>$row->nama_rak,
                                'pcs'=>$row->pcs,
                                'stok'=>$row->pcs,
                                'kurangnya'=>$kurangnya,
                                'sisa'=>$kurangnya - $row->pcs,
                                'diambil'=>$diambil
                            );

                            $kurangnya=round($kurangnya-$row->pcs);
                        }
                    }

                    $list[]=array(
                        'kd'=>$detail->kd,
                        'nm'=>$detail->nm,
                        'dos'=>$detail->pivot->dos,
                        'pcs'=>$detail->pivot->pcs,
                        'rak'=>$rak
                    );
                }

                $data=array(
                    'success'=>true,
                    'pesan'=>'Data berhasil disimpan',
                    'errors'=>'',
                    'adahutang'=>$adahutang,
                    'nota'=>$nota,
                    'detail'=>$list
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

        //cek stok barangnya
        $list=array();
        foreach($po->detail as $detail){
            $barang=\App\Models\Barang::find($detail->kd);            

            $pcs=$detail->pivot->pcs;
            $dos=$detail->pivot->dos;
            $kurangnya=$barang->pcs*$dos+$pcs;
            $sudahdiambil=$barang->pcs*$dos+$pcs;

            $stokbarang=\DB::table('stok')
                ->leftJoin('rak','rak.kd','=','stok.rak_id')
                ->where('kd_brg',$detail->kd)
                ->select('rak.nm as nama_rak','stok.*')
                ->get();

            $rak=array();
            $sisa=0;

            foreach($stokbarang as $row){
                if($kurangnya > 0){
                    // if($row->pcs > $kurangnya){
                    //     $diambil = $row->pcs;
                    // }else{
                    //     $diambil = $row->pcs;
                    // }
                    
                    //jika stok barang lebih dari jumlah kurangnya, maka diambil semua kurang nya
                    if($kurangnya > $row->pcs){
                        $diambil=$row->pcs;
                    }else{
                        //jika stok barang kurang dari  barang yang ingin diambil
                        if($row->pcs > $kurangnya){
                            $diambil=$kurangnya;
                        }else{
                            $diambil=$row->pcs;
                        }
                    }
                    

                    $rak[]=array(
                        'rak'=>$row->nama_rak,
                        'pcs'=>$row->pcs,
                        'stok'=>$row->pcs,
                        'kurangnya'=>$kurangnya,
                        'sisa'=>$kurangnya - $row->pcs,
                        'diambil'=>$diambil
                    );

                    $kurangnya=round($kurangnya-$row->pcs);
                }
            }

            $list[]=array(
                'kd'=>$detail->kd,
                'nm'=>$detail->nm,
                'dos'=>$detail->pivot->dos,
                'pcs'=>$detail->pivot->pcs,
                'rak'=>$rak
            );
        }

        return array('po'=>$po,'detail'=>$list);
    }

    public function po_by_id(Request $request,$id)
    {
        //delete barang yang stoknya 0
        \DB::Table('stok')  
            ->where('pcs',0)
            ->delete();


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
        $status_stok=array();

        foreach($po->detail as $row){
            //cek semua stok barang di tabel stok
            $cekStok=\DB::select("SELECT SUM(a.pcs) AS jumlah_stok FROM stok a
                WHERE a.kd_brg='$row->kd'
                AND a.lokasi_id='$po->lokasi_id'
                AND a.pcs>0
                GROUP BY a.kd_brg");

            if(count($cekStok) > 0){
                $jumlah_stok=$cekStok[0]->jumlah_stok;
                $jumlah_yang_diminta=$row->pivot->total_pcs;
                $default_jumlah_yg_diminta=$row->pivot->total_pcs;
                $yang_sudah_diambil=0;

                //ambil di stok berdasarkan barang yang diminta, di order berdasarkan tanggal
                $ambilStok=\DB::select("select a.*, b.pcs as pcs_brg,
                    CEIL(a.pcs / b.pcs) as realisasi_dos,
                    CEIL(a.pcs % b.pcs) as realisasi_pcs 
                    from stok a 
                    left join brg b on b.kd=a.kd_brg
                    where a.kd_brg='$row->kd' order by a.tgl");

                //cek dulu apakah total stok melebihi dari jumlah yang diminta
                if($jumlah_stok >= $jumlah_yang_diminta)
                {
                    foreach($ambilStok as $row2)
                    {
                        if($jumlah_yang_diminta >= 0){
                            if($row2->pcs >= $jumlah_yang_diminta){
                                $status='terpenuhi';
                                if($row->pivot->dos > 0)
                                {
                                    $dos=FLOOR($jumlah_yang_diminta/$row->pcs);
                                    $pcs=FLOOR($jumlah_yang_diminta % $row->pcs);   
                                }else{
                                    $dos=$row->pivot->dos;
                                    $pcs=$row->pivot->pcs;    
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
                            }else{
                                $status='kurang';
                                $dos=FLOOR($row2->pcs/$row->pcs);
                                $pcs=FLOOR($row2->pcs % $row->pcs);
                                
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
                        
                        $jumlah_yang_diminta=(int)$jumlah_yang_diminta - (int)$row2->pcs;
                    }

                }else{
                    //jumlah stok tidak mencukupi
                    foreach($ambilStok as $row2)
                    {
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
                            'status'=>'kurang',
                            'realisasi_dosnya'=>$row2->realisasi_dos,
                            'realisasi_pcsnya'=>$row2->realisasi_pcs,
                            'default_jml_yg_diminta'=>$default_jumlah_yg_diminta,
                            'jumlah_yg_diminta'=>$jumlah_yang_diminta
                        );
                    }
                }
            }else{
                $list[]=array(
                    'idstok'=>'',
                    'kd'=>$row->kd,
                    'nm'=>$row->nm,
                    'jual'=>$row->jual,
                    'dos'=>$row->pivot->dos,
                    'pcs'=>$row->pivot->pcs,
                    'pcs_per_dos'=>$row->pcs,
                    'lokasi_id'=>$po->lokasi_id,
                    'rak_id'=>'',
                    'jumlah_stok'=>0,
                    'status'=>'stok tidak ada',
                    'realisasi_dosnya'=>0,
                    'realisasi_pcsnya'=>0,
                    'default_jml_yg_diminta'=>0,
                    'jumlah_yg_diminta'=>0
                );

                $status_stok[]=array(
                    'lokasi_id'=>$po->lokasi_id,
                    'kd_brg'=>$row->kd,
                    'nm'=>$row->nm,
                    'status'=>'Tidak ada dalam stok',
                    'dos'=>$row->pivot->dos,
                    'pcs'=>$row->pivot->pcs,
                    'total_pcs'=>$row->pivot->total_pcs
                );
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
                    AND a.lokasi_id='$po->lokasi_id'
                    GROUP BY a.kd_brg
                ) AS semua
                WHERE semua.sisa < 0");

        
        return array(
            'po'=>$po,
            'list'=>$list,
            'kurang'=>$barang_sisa,
            'status_stok'=>$status_stok
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
        $perusahaan=auth()->user()->perusahaan->nama;

        $sql=Po::select(\DB::Raw("max(no_po) as maxKode"))
            ->where('no_po','like','PO-'.$perusahaan.'%')
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 10,10);
        $noUrut++;
        $char = "PO-".$perusahaan."-".date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }

    public function list_po_not_in_picking(Request $request)
    {
        $dimana="";

        if($request->has('status')){
            $status=$request->input('status');

            if($status=="true"){
                $dimana.=" and po.no_ref_po is null";
            }

            if($status=="false"){
                $dimana.=" and po.no_ref_po is not null";
            }
        }

        if($request->has('customer') && $request->input('customer')!=""){
            $customer=$request->input('customer');
            $dimana.=" and customer_id='".$customer."'";
        }

        $po=\DB::select("select * from po where status_konfirmasi!='Please Confirm' and no_po not in (select no_po from picking) $dimana");

        return $po;
    }

    public function po_by_customer(Request $request,$id)
    {
        $picking=\App\Models\Picking::select('no_po')->get();

        $po=Po::where('customer_id',$id);

        if($request->has('status')){
            $status=$request->input('status');

            if($status=="true"){
                $po=$po->WhereNull('no_ref_po')
                    ->whereNotIn('no_po',$picking);
            }else if($status=="false"){
                $po=$po->whereNotNull('no_ref_po');
            }else{
                $po=$po->WhereNull('no_ref_po')
                    ->whereNotIn('no_po',$picking);
            }
        }

        return $po->get();
    }
}
