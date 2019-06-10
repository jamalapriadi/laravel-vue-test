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
        $noUrut= (int) substr($kodeBarang, 3,3);
        $noUrut++;
        $char = "BRG";
        $newId= $char.sprintf("%03s",$noUrut);

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
        $barang=Barang::select('kd','nm','pcs','jual','merk_id');

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
            $barang=$barang->whereRaw("kd in (select kd_brg from stok where rak_id=$rak)");
        }

        return $barang->get();
    }

    public function get_rak_by_barang(Request $request, $id)
    {
        $rules=[
            'dos'=>'required',
            'pcs'=>'required'
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
            $kurangnya=$barang->pcs*$dos+$pcs;

            //cek stok barangnya
            $stokbarang=\DB::table('stok')
                ->leftJoin('rak','rak.kd','=','stok.rak_id')
                ->where('kd_brg',$id)
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
}
