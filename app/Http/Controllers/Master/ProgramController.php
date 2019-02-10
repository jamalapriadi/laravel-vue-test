<?php

namespace App\Http\Controllers\Master;

use App\Models\Program;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $program=Program::with('detail');

        if($request->has('q')){
            $program=$program->where('nm','like','%'.request('q').'%');
        }

        $program=$program->paginate(25);

        return $program;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
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
            'start'=>'required',
            'end'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi error',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $cus=new Program;
            $cus->nmr=request('kode');
            $cus->nm=request('nama');
            $cus->awprriod=date('Y-m-d',strtotime(request('start')));
            $cus->akpriod=date('Y-m-d',strtotime(request('end')));
            $cus->insert_user=auth()->user()->username;
            $cus->update_user=auth()->user()->username;
            $simpan=$cus->save();

            if($simpan){
                if($request->has('listBarang')){
                    $listbarang=request('listBarang');

                    foreach($listbarang as $key=>$val){
                        \DB::table('rprogram')
                            ->insert(
                                [
                                    'nmr_program'=>request('kode'),
                                    'kd_brg'=>$val['kd_barang'],
                                    'qty'=>$val['qty'],
                                    'point'=>$val['point']
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
     * @param  \App\Master\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cus=Program::with('detail')->find($id);

        return $cus;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Master\Program  $program
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
     * @param  \App\Master\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules=[
            'nama'=>'required',
            'nik'=>'required',
            'npwp'=>'required',
            'alamat'=>'required',
            'alias'=>'required',
            'kota'=>'required',
            'telepon'=>'required',
            'nmtk'=>'required',
            'kontak'=>'required',
            'kota'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi error',
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $cus=Program::find($id);
            $cus->nm=request('nama');
            $cus->alamat=request('alamat');
            $cus->alias=request('alias');
            $cus->kota_id=request('kota');
            $cus->tlpn=request('telepon');
            $cus->nmtk=request('nmtk');
            $cus->kontak=request('kontak');
            $cus->fax=request('fax');
            $cus->plafon=request('plafon');
            $cus->top=request('top');
            $cus->npwp=request('npwp');
            $cus->nik=request('nik');
            $cus->jenis=request('jenis');
            $cus->insert_user=auth()->user()->username;
            $cus->save();

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
     * @param  \App\Master\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cus=Program::find($id);

        $hapus=$cus->delete();

        if($hapus){
            \DB::table('rprogram')
                ->where('nmr_program',$id)
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

    public function autonumber_program()
    {
        $sql=Program::select(\DB::Raw("max(nmr) as maxKode"))
            ->first();
        $kodeBarang = $sql->maxKode;
        $noUrut= (int) substr($kodeBarang, 11,11);
        $noUrut++;
        $char = "PRG-TLG-".date('y')."-";
        $newId= $char.sprintf("%06s",$noUrut);

        return $newId;
    }
}
