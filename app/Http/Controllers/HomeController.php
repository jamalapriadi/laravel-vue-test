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
        $cek=\DB::table('ket')->get();

        return $cek;
    }

    public function keterangan(Request $request){
        $rules=[
            'no_hp'=>'required',
            'email'=>'required',
            'pin'=>'required',
            'npwp'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>"Validasi Error",
            );
        }else{
            $cek=\DB::table('ket')->count();

            if($cek > 0)
            {
                \DB::table('ket')
                    ->update(
                        [
                            'no_hp'=>request('no_hp'),
                            'email'=>request('email'),
                            'npwp'=>request('npwp'),
                            'pin'=>request('pin')
                        ]
                    );
            }else{
                \DB::table('ket')
                    ->insert(
                        [
                            'no_hp'=>request('no_hp'),
                            'email'=>request('email'),
                            'npwp'=>request('npwp'),
                            'pin'=>request('pin')
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
