<?php

namespace App\Http\Controllers\Master;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Exports\BarangExport;
use App\Exports\AllBarangExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BarangImport;
use App\Imports\BarangUpdateImport;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $barang=Barang::with(
            [
                'kelompok',
                'merk'
            ]
        );

        if($request->has('q') && $request->input('q')!=""){
            $barang=$barang->where('nm','like','%'.request('q').'%')
                ->orWhere('kd','like','%'.request('q').'%');
        }

        if($request->has('merk') && $request->input('merk')!=""){
            $merk=request('merk');
            $barang=$barang->where('merk_id',$merk);
        }

        if($request->has('kelompok') && $request->input('kelompok')!=""){
            $kel=request('kelompok');
            $barang=$barang->where('kelompok_id',$kel);
        }

        $barang=$barang->paginate(25);

        return $barang;
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
            'nama'=>'required',
            'kelompok'=>'required',
            'merk'=>'required',
            // 'status'=>'required',
            // 'satuan'=>'required',
            'pcs'=>'required',
            // 'hrgb'=>'required',
            'hrgp'=>'required',
            'jual'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $barang=new Barang;
            $barang->kd=request('kode');
            $barang->nm=request('nama');
            $barang->kelompok_id=request('kelompok');
            $barang->merk_id=request('merk');
            $barang->status=request('status');
            $barang->satuan=request('satuan');
            $barang->pcs=request('pcs');
            $barang->hrgb=request('hrgb');
            $barang->hrgp=request('hrgp');
            $barang->jual=request('jual');
            $barang->save();

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
     * @param  \App\Master\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $barang=Barang::find($id);

        return $barang;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Barang  $barang
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
     * @param  \App\Master\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules=[
            'kode'=>'required',
            'nama'=>'required',
            'kelompok'=>'required',
            'merk'=>'required',
            // 'status'=>'required',
            // 'satuan'=>'required',
            'pcs'=>'required',
            // 'hrgb'=>'required',
            'hrgp'=>'required',
            'jual'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $barang=Barang::find($id);
            $barang->kd=request('kode');
            $barang->nm=request('nama');
            $barang->kelompok_id=request('kelompok');
            $barang->merk_id=request('merk');
            $barang->status=request('status');
            $barang->satuan=request('satuan');
            $barang->pcs=request('pcs');
            $barang->hrgb=request('hrgb');
            $barang->hrgp=request('hrgp');
            $barang->jual=request('jual');
            $barang->save();

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
     * @param  \App\Master\Barang  $barang
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $barang=Barang::find($id);

        $hapus=$barang->delete();

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

    public function autonumber_barang()
    {
        $sql=Barang::select(\DB::Raw("max(kd) as maxKode"))
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 5,5);
        $noUrut++;
        $char = "BRG";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }

    public function sample_barang(Request $request)
    {
        return Excel::download(new BarangExport, 'barang.xlsx');
    }

    public function export_barang(Request $request)
    {
        return Excel::download(new AllBarangExport, 'barang.xlsx');
    }

    public function import_new_barang(Request $request)
    {
        $rules=['file'=>'required'];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi Gagal',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            Excel::import(new BarangImport, request()->file('file'));

            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil di import',
                'errors'=>array()
            );
        }

        return $data;
    }

    public function import_update_barang(Request $request)
    {
        $rules=['file'=>'required'];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi Gagal',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            Excel::import(new BarangUpdateImport, request()->file('file'));

            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil di import',
                'errors'=>array()
            );
        }

        return $data;
    }

    public function list_barang(Request $request)
    {
        $barang=Barang::with(
            [
                'kelompok',
                'merk'
            ]
        );

        if($request->has('q')){
            $barang=$barang->where('nm','like','%'.request('q').'%')
                ->orWhere('kd','like','%'.request('q').'%');
        }

        $barang=$barang->limit(25);

        return $barang->get();
    }

    public function cari_barang_by_kode($id)
    {
        $barang=Barang::find($id);

        if($barang!=null){
            $data=array(
                'success'=>true,
                'pesan'=>'Barang ditemukan',
                'barang'=>$barang
            );
        }else{
            $data=array(
                'success'=>false,
                'pesan'=>'Barang tidak ditemukan',
                'barang'=>array()
            );
        }

        return $data;
    }

    public function cari_barang_by_nama(Request $request)
    {
        if($request->has('lokasi')){
            $lokasi=request('lokasi');

            $barang=Barang::leftJoin('stok','stok.kd_brg','=','brg.kd')
                ->select('kd','nm','brg.pcs','jual','merk_id',\DB::raw("IFNULL(SUM(stok.pcs),0) as stok"))
                ->groupBy('kd');
        }else{
            $barang=Barang::select('kd','nm','pcs','jual','merk_id',\DB::raw("'0' as stok"));
        }

        if($request->has('q')){
            $barang=$barang->where('nm','like','%'.request('q').'%');
        }

        if($request->has('kode') && $request->input('kode')!=""){
            $barang=$barang->where('kd','like','%'.request('kode').'%');
        }

        if($request->has('merk') && $request->input('merk')!=""){
            $barang=$barang->where('merk_id',$request->input('merk'));
        }

        if($request->has('rak')){
            $rak=request('rak');
            $barang=$barang->whereRaw("kd in (select kd_brg from stok where rak_id=$rak)")
                ->where('stok.rak_id',$rak);
        }

        return $barang->get();
    }

    public function cari_barang_by_nama_2(Request $request)
    {
        if($request->has('lokasi')){
            $lokasi=request('lokasi');

            $barang=Barang::leftJoin('stok','stok.kd_brg','=','brg.kd')
                ->leftJoin('rak','rak.kd','=','stok.rak_id')
                ->leftJoin('lokasi','lokasi.id','=','stok.lokasi_id')
                ->select('brg.kd','brg.nm','brg.pcs','jual','merk_id','stok.rak_id',
                \DB::raw("rak.nm as nama_rak"),
                \DB::raw("lokasi.nm as nama_lokasi"),
                'stok.lokasi_id',\DB::raw("IFNULL(SUM(stok.pcs),0) as stok"));
        }else{
            $barang=Barang::leftJoin('stok','stok.kd_brg','=','brg.kd')
                ->leftJoin('rak','rak.kd','=','stok.rak_id')
                ->leftJoin('lokasi','lokasi.id','=','stok.lokasi_id')
                ->select('brg.kd','brg.nm','brg.pcs','jual','merk_id','stok.rak_id',
                \DB::raw("rak.nm as nama_rak"),
                \DB::raw("lokasi.nm as nama_lokasi"),
                'stok.lokasi_id',\DB::raw("IFNULL(SUM(stok.pcs),0) as stok"));
        }

        if($request->has('q')){
            $barang=$barang->where('brg.nm','like','%'.request('q').'%');
        }

        if($request->has('kode') && $request->input('kode')!=""){
            $barang=$barang->where('brg.kd','like','%'.request('kode').'%');
        }

        if($request->has('merk') && $request->input('merk')!=""){
            $barang=$barang->where('merk_id',$request->input('merk'));
        }

        if($request->has('rak')){
            $rak=request('rak');
            $barang=$barang->whereRaw("brg.kd in (select kd_brg from stok where rak_id=$rak)")
                ->where('stok.rak_id',$rak);
        }

        return $barang->get();
    }

    public function get_rak_by_barang(Request $request, $id)
    {
        $rules=[
            'dos'=>'required',
            'pcs'=>'required',
            'lokasi'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{

            $barang=Barang::find($id);            

            $pcs=$request->input('pcs');
            $dos=$request->input('dos');
            $lokasi=$request->input('lokasi');
            $kurangnya=$barang->pcs*$dos+$pcs;

            //hapus barang di tabel stok yang pcs nya 0
            \DB::Table('stok')
                ->where('pcs',0)
                ->delete();

            //cek stok barangnya
            $stokbarang=\DB::table('stok')
                ->leftJoin('rak','rak.kd','=','stok.rak_id')
                ->where('kd_brg',$id)
                ->where('stok.lokasi_id',$lokasi)
                ->select('rak.nm as nama_rak','stok.*')
                ->get();

            $rak=array();
            foreach($stokbarang as $row){
                if($kurangnya > 0){
                    $rak[]=array(
                        'stok_id'=>$row->id,
                        'rak'=>$row->nama_rak,
                        'pcs'=>$row->pcs
                    );

                    $kurangnya=round($kurangnya-$row->pcs);
                }
            }


            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil',
                'kd_barang'=>$id,
                'nm_barang'=>$barang->nm,
                'dos'=>$dos,
                'pcs'=>$pcs,
                'kurangnya'=>$barang->pcs*$dos+$pcs,
                'total_pcs'=>$barang->pcs*$dos+$pcs,
                'total_harga'=>$barang->jual*($barang->pcs*$dos+$pcs),
                'rak'=>$rak
            );
        }

        return $data;

        
    }

    public function fifo_barang(Request $request,$id)
    {
        $rules=[
            'dos'=>'required',
            'pcs'=>'required',
            'lokasi'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails())
        {
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            //delete barang yang stoknya 0
            \DB::Table('stok')  
                ->where('pcs',0)
                ->delete();


            $barang=Barang::find($id);
            $req_dos=request('dos');
            $req_pcs=request('pcs');
            $lokasi=request('lokasi');

            //jumlah pcs yang diminta
            $qty=($barang->pcs*$req_dos) + $req_pcs;
            $total_pcsnya=($barang->pcs*$req_dos) + $req_pcs;

            //cek jumlah stok yang tersedia berdasarkan lokasi
            $allstok=\DB::select("SELECT a.*, b.nm as nama_rak FROM stok a
                LEFT JOIN rak as b on b.kd=a.rak_id
                WHERE a.lokasi_id=$lokasi
                AND a.kd_brg='$id'");

            $stok_all=0;
            foreach($allstok as $row)
            {
                $stok_all+=$row->pcs;
            }

            $list=array();
            $kurangnya=array();
            $status_stok=array();

            if(count($allstok)> 0){
                //ada stok

                if($qty <= $stok_all)
                {
                    foreach($allstok as $row){
                        $stok = $row->pcs;

                        if($qty > 0){
                            $temp = $qty;
                            $qty=$qty - $stok;

                            if($qty > 0){
                                $dos=FLOOR($row->pcs/$barang->pcs);
                                $pcs=FLOOR($row->pcs % $barang->pcs); 
                                $status='terpenuhi';

                            }else{
                                $dos=FLOOR($temp/$barang->pcs);
                                $pcs=FLOOR($temp % $barang->pcs); 
                                $status='kurang';
                            }

                            $list[]=array(
                                'success'=>true,
                                'idstok'=>$row->id,
                                'kd'=>$barang->kd,
                                'nm'=>$barang->nm,
                                'jual'=>$barang->jual,
                                'dos'=>$req_dos,
                                'pcs'=>$req_pcs,
                                'pcs_per_dos'=>$barang->pcs,
                                'lokasi_id'=>$row->lokasi_id,
                                'rak_id'=>$row->rak_id,
                                'nama_rak'=>$row->nama_rak,
                                'jumlah_stok'=>$row->pcs,
                                'status'=>$status,
                                'yg_diminta'=>($barang->pcs * $dos) + $pcs,
                                'realisasi_dosnya'=>$dos,
                                'realisasi_pcsnya'=>$pcs,
                                'realisasi_total_pcs'=>($barang->pcs * $dos) + $pcs
                            );
                        }
                    }   
                }else{
                    foreach($allstok as $row){
                        $dos=FLOOR($stok_all/$barang->pcs);
                        $pcs=FLOOR($stok_all % $barang->pcs); 
                        $status='stok tidak mencukupi';

                        $list[]=array(
                            'success'=>true,
                            'idstok'=>$row->id,
                            'kd'=>$barang->kd,
                            'nm'=>$barang->nm,
                            'jual'=>$barang->jual,
                            'dos'=>$req_dos,
                            'pcs'=>$req_pcs,
                            'pcs_per_dos'=>$barang->pcs,
                            'lokasi_id'=>$lokasi,
                            'rak_id'=>$row->rak_id,
                            'nama_rak'=>$row->nama_rak,
                            'jumlah_stok'=>$row->pcs,
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
                    $total_pcsnya AS yang_diminta,
                    SUM(a.pcs) - $total_pcsnya AS sisa
                    FROM stok a
                    LEFT JOIN brg b ON b.kd=a.kd_brg
                    WHERE a.kd_brg='$id'
                    AND a.lokasi_id=$lokasi
                    GROUP BY a.kd_brg
                ) AS semua
                WHERE semua.sisa < 0");

        return array(
            'success'=>true,
            'list'=>$list,
            'status_stok'=>$status_stok,
            'kurang'=>$barang_sisa
        );
    }
}
