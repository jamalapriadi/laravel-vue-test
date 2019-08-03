<?php

namespace App\Http\Controllers\Master;

use App\Models\Retur;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReturController extends Controller
{
    public function index(Request $request){
        $retur=Retur::select('no_retur','tgl_retur','full_nota','customer_id','lokasi_id')
            ->with(
                [
                    'customer',
                    'lokasi'
                ]
            );

        $retur=$retur->paginate(25);

        return $retur;   
    }

    public function store(Request $request){
        // return $request->all();
        $rules=[
            'kode'=>'required',
            'customer'=>'required',
            'tanggal'=>'required',
            'barang'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi error'
            );
        }else{
            $kode=$this->autonumber_retur();

            $r= new Retur;
            $r->no_retur=$kode;
            $r->tgl_retur=date('Y-m-d',strtotime(request('tanggal')));
            if(request('full_nota')==true){
                $r->full_nota='Y';    
                $r->no_order=request('no_order');
            }else{
                $r->full_nota='N';
            }
            $r->kd_trans=request('kd_trans');
            $r->customer_id=request('customer');
            $r->lokasi_id=request('lokasi');
            $r->keterangan=request('keterangan');
            $r->insert_user=auth()->user()->username;
            $r->update_user=auth()->user()->username;
            
            $simpan=$r->save();

            if($simpan){
                if($request->has('barang')){
                    $barang=request('barang');

                    $total=0;
                    foreach($barang as $key=>$val){
                        $brg=\App\Models\Barang::find($val['kd_barang']);

                        if(request('lunas')==true){
                            
                            \DB::statement("UPDATE rorder SET status_retur='Y',dos=dos - ".$val['return_dos'].",
                                pcs=pcs-".$val['return_pcs']." 
                                where no_order='".$request->input('no_order')."'
                                and kd_brg='".$val['kd_barang']."'
                                ");

                            $sisa_pembayaran=$val['harga']*($brg->pcs * $val['return_dos']) + $val['return_pcs'];

                            \DB::statement("UPDATE orders set total=total-".$sisa_pembayaran.", sisa_pembayaran=sisa_pembayaran-".$sisa_pembayaran."
                                where no_order='".$request->input('no_order')."'");
                        }else{
                            // Contoh saya punya order barang, brg001 dan brg002, 
                            // kemudian saya retur brg001 tapi pilihannya belum lunas, berarti di order itu barang 001 saya hapus?
                            // kalo saya order 4 barang, tapi yang di retur cuma 1 barang doang.
                            // jika jumlah barang yang di retur sama dengan jumlah yang di order, maka data barang itu dihapus aja

                            \DB::statement("UPDATE rorder SET status_retur='Y',dos=dos - ".$val['return_dos'].",
                                pcs=pcs-".$val['return_pcs']." 
                                where no_order='".$request->input('no_order')."'
                                and kd_brg='".$val['kd_barang']."'
                                ");
                            
                            // \DB::table('rorder')
                            //     ->where('no_order',$request->input('no_order'))
                            //     ->where('kd_brg',$val['kd_barang'])
                            //     ->update(
                            //         [
                            //             'status_retur'=>'Y'
                            //         ]
                            //     );
                        }


                        \DB::table('rretur')
                            ->insert(
                                [
                                    'no_retur'=>$kode,
                                    'no_order'=>$val['no_order'],
                                    'kd_brg'=>$val['kd_barang'],
                                    'dos'=>$val['return_dos'],
                                    'pcs'=>$val['return_pcs'],
                                    'keterangan'=>$val['keterangan'],
                                    'total_pcs'=>($brg->pcs * $val['return_dos']) + $val['return_pcs']
                                ]
                            );

                        $total+=($brg->pcs * $val['return_dos']) + $val['return_pcs'] * $brg->jual;
                        $totalpoint=($brg->pcs * $val['return_dos']) + $val['return_pcs'];

                        /* dapatkan point dari barang ini $val['kd_barang'] */
                        $cek=\DB::table('customer_point')
                            ->where('customer_id',$request->input('customer'))
                            ->where('no_order',$request->input('no_order'))
                            ->where('kd_barang',$val['kd_barang'])
                            ->get();

                        if(count($cek)>0){
                            //looping cek dan dapatkan customer point dari parameter diatas
                            foreach($cek as $ck){
                                //dapatkan program pointnya
                                $ambilpoint=\App\Models\Program::with('detail')->find($ck->program_id);

                                foreach($ambilpoint->detail as $am){
                                    if($am->kd == $val['kd_barang']){
                                        $cekqtynya=$am->pivot->qty;

                                        $kurangpoint=round($totalpoint / $cekqtynya) * $am->pivot->point;

                                        \DB::statement("UPDATE customer_point SET point = point-".$kurangpoint." 
                                            where customer_id='".$request->input('customer')."'
                                            and no_order='".$request->input('no_order')."'
                                            and program_id='".$ck->program_id."'
                                            and kd_barang='".$val['kd_barang']."'
                                            ");
                                    }
                                }
                            }
                        }
                    }

                    \DB::statement("UPDATE orders SET total = total-".$total." 
                        where no_order='".$request->input('no_order')."'");


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

    public function show(Request $request,$id){
        $retur=Retur::with(
                [
                    'customer',
                    'lokasi',
                    'detail'
                ]
            )->find($id);

        return $retur;
    }

    public function update(Request $request,$id){

    }

    public function destroy(Request $request,$id){
        $retur=Retur::find($id);

        $hapus=$retur->delete();

        if($hapus){
            \DB::table('rretur')
                ->where('no_retur',$id)
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

    public function autonumber_retur(Request $request){
        $perusahaan=auth()->user()->perusahaan->nama;

        $sql=Retur::select(\DB::Raw("max(no_retur) as maxKode"))
            ->where('no_retur','like','RTR-'.$perusahaan.'%')
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 11,11);
        $noUrut++;
        $char = "RTR-".$perusahaan."-".date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }

    public function cari_no_retur_by_id(Request $request)
    {
        $retur=Retur::select('no_retur','tgl_retur','full_nota','customer_id')
            ->with(
                [
                    'customer'=>function($q){
                        $q->select('kd','nm','alamat','alias');
                    }
                ]
            );

        if($request->has('q')){
            $retur=$retur->where('no_retur','like','%'.request('no_retur').'%');
        }

        $retur=$retur->get();

        return $retur;
    }
}