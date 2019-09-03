<?php

namespace App\Http\Controllers\Master;

use App\Models\Mutasi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MutasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mutasi=Mutasi::with(
            [
                'detail',
                'gudang_lama',
                'gudang_baru'
            ]
        )->where('perusahaan_id',auth()->user()->perusahaan_id);

        // if($request->has('q')){
        //     $mutasi=$mutasi->where('no_storing','like','%'.request('q').'%')
        //         ->orWhere('no_ref','like','%'.request('q').'%');
        // }

        $mutasi=$mutasi->paginate(25);

        return $mutasi;
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
            'gudang_lama'=>'required',
            'tanggal'=>'required',
            'gudang_baru'=>'required',
            'listBarang'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi errors',
                'error'=>$validasi->errors()->all()
            );
        }else{
            $kode=$this->autonumber_mutasi();

            $s=new Mutasi;
            $s->no_mutasi=$kode;
            $s->lokasil=request('gudang_lama')['id'];
            $s->lokasib=request('gudang_baru')['id'];
            $s->tgl=date('Y-m-d',strtotime(request('tanggal')));
            $s->ket=request('keterangan');
            $s->perusahaan_id=auth()->user()->perusahaan_id;
            $s->insert_user=auth()->user()->username;

            $simpan=$s->save();

            if($simpan){
                if($request->has('listBarang')){
                    //delete barang yang stoknya 0
                    \DB::Table('stok')  
                        ->where('pcs',0)
                        ->delete();


                    $listbarang=request('listBarang');
                    $list=array();

                    foreach($listbarang as $key=>$val){
                        \DB::table('rmutasi')
                                ->insert(
                                    [
                                        'no_mutasi'=>$kode,
                                        'kd_brg'=>$val['kd_barang'],
                                        'rakl'=>$val['rak_lama_id'],
                                        'rakb'=>$val['rak_baru_id'],
                                        'dos'=>$val['dos'],
                                        'pcs'=>$val['pcs']
                                    ]
                                );

                        $id=$val['kd_barang'];

                        $barang=\App\Models\Barang::find($val['kd_barang']);
                        $req_dos=$val['dos'];
                        $req_pcs=$val['pcs'];
                        $lokasi=$val['gudang_lama_id'];

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
                                            'lokasi_baru'=>$val['gudang_baru_id'],
                                            'rak_id'=>$row->rak_id,
                                            'rak_baru'=>$val['rak_baru_id'],
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
                                        'lokasi_baru'=>$val['gudang_baru_id'],
                                        'rak_id'=>$row->rak_id,
                                        'rak_baru'=>$val['rak_baru_id'],
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
                        }
                    }

                    foreach($list as $key=>$val){
                        //cek apakah barang ini sudah ada di stok atau belum
                        $cekstok=\DB::table('stok')
                            ->where('kd_brg',$val['kd'])
                            ->where('rak_id',$val['rak_baru'])
                            ->where('lokasi_id',$val['lokasi_baru'])
                            ->get();

                        if(count($cekstok) > 0){
                            \DB::statement("UPDATE stok SET pcs = pcs-".$val['realisasi_total_pcs']." where id=".$val['idstok']);
                        }else{
                            \DB::table('stok')
                                ->insert(
                                    [
                                        'kd_brg'=>$val['kd'],
                                        'lokasi_id'=>$val['lokasi_baru'],
                                        'rak_id'=>$val['rak_baru'],
                                        'tgl'=>date('Y-m-d'),
                                        'pcs'=>$val['realisasi_total_pcs'],
                                        'created_at'=>date('Y-m-d H:i:s'),
                                        'updated_at'=>date('Y-m-d H:i:s')
                                    ]
                                );

                            \DB::statement("UPDATE stok SET pcs = pcs-".$val['realisasi_total_pcs']." where id=".$val['idstok']);
                        }
                    }
                }

                $data=array(
                    'success'=>true,
                    'pesan'=>'Data berhasil disimpan',
                    'errors'=>'',
                    'list'=>$list
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
     * @param  \App\Master\Mutasi  $mutasi
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $mutasi=Mutasi::with(
            [
                'detail',
                'gudang_lama',
                'gudang_baru'
            ]
        )->find($id);

        return $mutasi;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Mutasi  $mutasi
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
     * @param  \App\Master\Mutasi  $mutasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Master\Mutasi  $mutasi
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sql=Mutasi::find($id);

        $hapus=$sql->delete();

        if($hapus){
            \DB::table('rmutasi')
                ->where('no_mutasi',$id)
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

    public function autonumber_mutasi()
    {
        $perusahaan=auth()->user()->perusahaan->nama;

        $sql=Mutasi::select(\DB::Raw("max(no_mutasi) as maxKode"))
            ->where('no_mutasi','like','MTS-'.$perusahaan.'%')
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 11,11);
        $noUrut++;
        $char = "MTS-".$perusahaan."-".date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }
}
