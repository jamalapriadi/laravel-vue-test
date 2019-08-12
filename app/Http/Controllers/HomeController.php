<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function list_keterangan()
    {
        $cek=\DB::table('ket')
            ->where('perusahaan_id',auth()->user()->perusahaan_id)
            ->get();

        return $cek;
    }

    public function keterangan(Request $request){
        $rules=[
            'no_hp'=>'required',
            'email'=>'required',
            'no_rekening'=>'required',
            'npwp'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>"Validasi Error",
            );
        }else{
            $cek=\DB::table('ket')
                ->where('perusahaan_id',auth()->user()->perusahaan_id)
                ->count();

            if($cek > 0)
            {
                \DB::table('ket')
                    ->where('perusahaan_id',auth()->user()->perusahaan_id)
                    ->update(
                        [
                            'no_hp'=>request('no_hp'),
                            'email'=>request('email'),
                            'npwp'=>request('npwp'),
                            'no_rekening'=>request('no_rekening')
                        ]
                    );
            }else{
                \DB::table('ket')
                    ->insert(
                        [
                            'no_hp'=>request('no_hp'),
                            'email'=>request('email'),
                            'npwp'=>request('npwp'),
                            'no_rekening'=>request('no_rekening'),
                            'perusahaan_id'=>auth()->user()->perusahaan_id
                        ]
                    );
            }

            $data=array(
                'success'=>true,
                'pesan'=>'data berhasil disimpan'
            );
        }

        return $data;
    }
}
