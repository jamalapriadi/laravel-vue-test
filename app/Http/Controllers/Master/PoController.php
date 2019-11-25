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
            $kode=$this->autonumber_po();

            $cus=new Po;
            $cus->no_po=$kode;
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
                and c.customer_id='".$customer."'
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

                    foreach($listbarang as $listbrg){
                        \DB::table('po_detail_input')
                            ->insert(
                                [
                                    'no_po'=>$kode,
                                    'kd_brg'=>$listbrg['kd_barang'],
                                    'dos'=>$listbrg['dos'],
                                    'pcs'=>$listbrg['pcs'],
                                    'total_pcs'=>$listbrg['total_pcs'],
                                    'kurangnya'=>$listbrg['kurang'],
                                    'lokasi_id'=>request('lokasi')
                                ]
                            );

                        $res=array();
                        foreach($listbrg['list'] as $value){
                            \DB::table('rpo')
                                ->insert(
                                    [
                                        'no_po'=>$kode,
                                        'kd_brg'=>$value['kd'],
                                        'dos'=>$value['realisasi_dosnya'],
                                        'pcs'=>$value['realisasi_pcsnya'],
                                        'total_pcs'=>$value['realisasi_total_pcs'],
                                        'lokasi_id'=>request('lokasi'),
                                        'rak_id'=>$value['rak_id'],
                                        'jumlah'=>$value['realisasi_total_pcs']
                                    ]
                                );
                        }
                    }
                    
                }

                if($request->has('kurang')){
                    $kurang=$request->input('kurang');

                    if(count($kurang)>0){
                        $kode2=$this->autonumber_po();

                        $newPo=new Po;
                        $newPo->no_po=$kode2;
                        $newPo->no_ref_po=$kode;
                        $newPo->customer_id=request('customer');
                        $newPo->ket=request('keterangan');
                        $newPo->tgl=date('Y-m-d',strtotime(request('tanggal')));
                        $newPo->lokasi_id=request('lokasi');
                        $newPo->perusahaan_id=auth()->user()->perusahaan_id;
                        $newPo->insert_user=auth()->user()->username;
                        $newPo->update_user=auth()->user()->username;
                        $simpan2=$newPo->save();

                        if($simpan2){
                            foreach($kurang as $val){

                                \DB::table('po_detail_input')
                                    ->insert(
                                        [
                                            'no_po'=>$newPo->no_po,
                                            'kd_brg'=>$val['kd_brg'],
                                            'dos'=>$val['dos'],
                                            'pcs'=>$val['pcs'],
                                            'total_pcs'=>$val['kurangnya'],
                                            'kurangnya'=>$val['kurangnya'],
                                            'lokasi_id'=>request('lokasi')
                                        ]
                                    );


                                \DB::table('rpo')
                                    ->insert(
                                        [
                                            'no_po'=>$newPo->no_po,
                                            'kd_brg'=>$val['kd_brg'],
                                            'dos'=>$val['dos'],
                                            'pcs'=>$val['pcs'],
                                            'total_pcs'=>$val['kurangnya'],
                                            'lokasi_id'=>request('lokasi'),
                                            'jumlah'=>$val['kurangnya']
                                        ]
                                    );
                            }
                        }
                    }
                }

                $nota=Po::with(
                    [
                        'detail',
                        'customer',
                        'customer.kota',
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
                        ->leftJoin('lokasi','lokasi.id','=','stok.lokasi_id')
                        ->where('kd_brg',$detail->kd)
                        ->where('rak_id',$detail->pivot->rak_id)
                        ->select('rak.nm as nama_rak','stok.*','lokasi.nm as nama_lokasi')
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
                        'jumlah'=>$detail->pivot->jumlah,
                        'total_pcs'=>$detail->pivot->total_pcs,
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
                'detailasli',
                'customer',
                'customer.kota',
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
                ->where('rak_id',$detail->pivot->rak_id)
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
                'jumlah'=>$detail->pivot->jumlah,
                'total_pcs'=>$detail->pivot->total_pcs,
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
                'detailasli',
                'detail.stok',
                'customer',
                'perusahaan',
                'lokasi'
            ]
        )->find($id);
        
        $list=array();
        $kurangnya=array();
        $status_stok=array();

        $list_rak="";
        $jumlah_rak=count($po->detail) - 1;

        foreach($po->detail as $key=>$row){
            if($key == 0){
                $list_rak.=$row->pivot->rak_id.",";
            }else if($key == $jumlah_rak){
                $list_rak.=$row->pivot->rak_id;
            }else{
                $list_rak.=$row->pivot->rak_id.",";
            }

            $kodebrg=$row->kd;
            $barang=\App\Models\Barang::find($kodebrg);
            $req_dos=$row->pivot->dos;
            $req_pcs=$row->pivot->pcs;
            $lokasi=$po->lokasi_id;
            $rakid=$row->pivot->rak_id;

            //jumlah pcs yang diminta
            $qty=($barang->pcs*$req_dos) + $req_pcs;
            $total_pcsnya=($barang->pcs*$req_dos) + $req_pcs;

            //cek jumlah stok yang tersedia berdasarkan lokasi
            $allstok=\DB::select("SELECT a.*, b.nm as nama_rak FROM stok a
                LEFT JOIN rak as b on b.kd=a.rak_id
                WHERE a.lokasi_id=$lokasi
                AND a.rak_id='$rakid'
                AND a.kd_brg='$kodebrg'");

            $stok_all=0;
            foreach($allstok as $val)
            {
                $stok_all+=$val->pcs;
            }

            if(count($allstok)> 0){
                //ada stok

                if($qty <= $stok_all)
                {
                    foreach($allstok as $alstok){
                        $stok = $alstok->pcs;

                        if($qty > 0){
                            $temp = $qty;
                            $qty=$qty - $stok;

                            if($qty > 0){
                                $dos=FLOOR($alstok->pcs/$barang->pcs);
                                $pcs=FLOOR($alstok->pcs % $barang->pcs); 
                                $status='terpenuhi';

                            }else{
                                $dos=FLOOR($temp/$barang->pcs);
                                $pcs=FLOOR($temp % $barang->pcs); 
                                $status='kurang';
                            }

                            $yg_diminta_untuk_stok=($barang->pcs * $dos) + $pcs;

                            if($yg_diminta_untuk_stok > $alstok->pcs){
                                $jumlah_stok = $alstok->pcs;
                            }else{
                                $jumlah_stok=$yg_diminta_untuk_stok;
                            }

                            $list[]=array(
                                'success'=>true,
                                'idstok'=>$alstok->id,
                                'kd'=>$barang->kd,
                                'nm'=>$barang->nm,
                                'jual'=>$barang->jual,
                                'dos'=>$req_dos,
                                'pcs'=>$req_pcs,
                                'pcs_per_dos'=>$barang->pcs,
                                'lokasi_id'=>$alstok->lokasi_id,
                                'rak_id'=>$alstok->rak_id,
                                'nama_rak'=>$alstok->nama_rak,
                                'jumlah_stok'=>$jumlah_stok,
                                'status'=>$status,
                                'yg_diminta'=>($barang->pcs * $dos) + $pcs,
                                'realisasi_dosnya'=>$dos,
                                'realisasi_pcsnya'=>$pcs,
                                'realisasi_total_pcs'=>($barang->pcs * $dos) + $pcs
                            );
                        }
                    }   
                }else{
                    foreach($allstok as $alstok){
                        $dos=FLOOR($stok_all/$barang->pcs);
                        $pcs=FLOOR($stok_all % $barang->pcs); 
                        $status='stok tidak mencukupi';

                        $list[]=array(
                            'success'=>true,
                            'idstok'=>$alstok->id,
                            'kd'=>$barang->kd,
                            'nm'=>$barang->nm,
                            'jual'=>$barang->jual,
                            'dos'=>$req_dos,
                            'pcs'=>$req_pcs,
                            'pcs_per_dos'=>$barang->pcs,
                            'lokasi_id'=>$lokasi,
                            'rak_id'=>$alstok->rak_id,
                            'nama_rak'=>$alstok->nama_rak,
                            'jumlah_stok'=>$alstok->pcs,
                            'status'=>$status,
                            'yg_diminta'=>$qty,
                            'realisasi_dosnya'=>$dos,
                            'realisasi_pcsnya'=>$pcs,
                            'realisasi_total_pcs'=>($barang->pcs * $dos) + $pcs
                        );
                    }
                }
            }else{
                //stok tidak ada
                $list[]=array(
                    'success'=>true,
                    'idstok'=>'',
                    'kd'=>$barang->kd,
                    'nm'=>$barang->nm,
                    'jual'=>$barang->jual,
                    'dos'=>$req_dos,
                    'pcs'=>$req_pcs,
                    'pcs_per_dos'=>$barang->pcs,
                    'lokasi_id'=>$lokasi,
                    'rak_id'=>'',
                    'nama_rak'=>'',
                    'jumlah_stok'=>0,
                    'status'=>'stok tidak ada',
                    'realisasi_dosnya'=>0,
                    'realisasi_pcsnya'=>0,
                    'realisasi_total_pcs'=>0,
                    'default_jml_yg_diminta'=>0,
                    'jumlah_yg_diminta'=>0
                );

                $status_stok[]=array(
                    'lokasi_id'=>$lokasi,
                    'kd_brg'=>$barang->kd,
                    'nm'=>$barang->nm,
                    'status'=>'Tidak ada dalam stok',
                    'dos'=>$req_dos,
                    'pcs'=>$req_pcs,
                    'total_pcs'=>$qty
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
                    (SELECT SUM(aa.total_pcs) FROM po_detail_input aa WHERE aa.no_po='".$id."' AND aa.kd_brg=a.kd_brg GROUP BY a.kd_brg) AS yang_diminta,
                    SUM(a.pcs) - (SELECT SUM(aa.total_pcs) FROM po_detail_input aa WHERE aa.no_po='".$id."' AND aa.kd_brg=a.kd_brg GROUP BY a.kd_brg ) AS sisa
                    FROM stok a
                    LEFT JOIN brg b ON b.kd=a.kd_brg
                    WHERE a.kd_brg IN (SELECT a.kd_brg FROM po_detail_input a WHERE a.no_po='".$id."')
                    AND a.lokasi_id='".$po->lokasi_id."'
                    and a.pcs > 0
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
            $cekrpo=\DB::table('rpo')
                    ->where('no_po',$id)
                    ->leftJoin('brg','brg.kd','=','rpo.kd_brg')
                    ->select('rpo.*','brg.pcs',\DB::raw("((brg.pcs*rpo.dos)+rpo.pcs) as total_pcs"))
                    ->get();

            foreach($cekrpo as $row){
                \DB::table('stok')
                    ->insert(
                        [
                            'kd_brg'=>$row->kd_brg,
                            'lokasi_id'=>$row->lokasi_id,
                            'rak_id'=>$row->rak_id,
                            'tgl'=>date('Y-m-d'),
                            'pcs'=>$row->total_pcs,
                            'created_at'=>date('Y-m-d H:i:s'),
                            'updated_at'=>date('Y-m-d H:i:s')
                        ]
                    );
            }

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
        $perusahaan_id=auth()->user()->perusahaan_id;

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

        $po=\DB::select("select * from po where perusahaan_id='$perusahaan_id' AND status_konfirmasi!='Please Confirm' AND no_po not in (select no_po from picking) $dimana");

        return $po;
    }

    public function po_by_customer(Request $request,$id)
    {
        $picking=\App\Models\Picking::select('no_po')
            ->where('perusahaan_id',auth()->user()->perusahaan_id)
            ->get();

        $po=Po::where('customer_id',$id)
            ->where('perusahaan_id',auth()->user()->perusahaan_id);

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
