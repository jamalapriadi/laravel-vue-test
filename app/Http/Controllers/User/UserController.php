<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\User;

class UserController extends Controller
{
    public function index(Request $request){
        $user=User::select('id','username','lvl','perusahaan_id','active')
            ->with('perusahaan');

        if($request->has('q')){
            $user=$user->where('username','like','%'.request('q').'%');
        }

        $user=$user->paginate(25);

        return $user;

    }

    public function store(Request $request){
        $rules=[
            'username'=>'required',
            'password'=>'required',
            'password_confirm'=>'required|same:password',
            'level'=>'required',
            'perusahaan'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi Error',
                'error'=>$validasi->errors()->all()
            );
        }else{
            $user=new User;
            $user->username=$request->input('username');
            $user->lvl=$request->input('level');
            $user->password=bcrypt($request->input('password'));
            $user->perusahaan_id=$request->input('perusahaan');
            $user->active='Y';
            
            $simpan=$user->save();

            if($simpan){
                $data=array(
                    'success'=>true,
                    'pesan'=>'Data berhasil dihapus',
                    'error'=>''
                );
            }else{
                $data=array(
                    'success'=>false,
                    'pesan'=>'Data gagal dihapus',
                    'error'=>''
                );
            }
        }

        return $data;
    }

    public function show($id){
        $user=User::findOrFail($id);
        $unit=\App\Models\Sosmed\Businessunit::select('id','unit_name')->get();

        return array(
            'unit'=>$unit,
            'user'=>$user
        );
    }

    public function update(Request $request,$id){
        $rules=[
            'unit'=>'required',
            'name'=>'required',
            'email'=>'required'
        ];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi Error',
                'error'=>$validasi->errors()->all()
            );
        }else{
            $user=User::find($id);
            $user->name=$request->input('name');
            $user->unit=$request->input('unit');
            $user->email=$request->input('email');
            $user->password=bcrypt($request->input('password'));
            
            $simpan=$user->save();

            if($simpan){
                $data=array(
                    'success'=>true,
                    'pesan'=>'Data berhasil dihapus',
                    'error'=>''
                );
            }else{
                $data=array(
                    'success'=>false,
                    'pesan'=>'Data gagal dihapus',
                    'error'=>''
                );
            }
        }

        return $data;
    }

    public function destroy($id){
        $user=User::find($id);

        $hapus=$user->delete();

        if($hapus){
            $data=array(
                'success'=>true,
                'pesan'=>"Data berhasil dihapus",
                'error'=>''
            );
        }else{
            $data=array(
                'success'=>false,
                'pesan'=>'Data gagal dihapus',
                'error'=>''
            );
        }

        return $data;
    }

    public function list_role(Request $request,$id){
        $user=User::with('roles')->find($id);

        return $user;
    }

    public function save_role_user(Request $request){
        $rules=['permission'=>'required','user'=>'required'];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi gagal',
                'error'=>''
            );
        }else{
            $user=User::find($request->input('user'));

            $permission=$request->input('permission');
            foreach($permission as $key=>$val){
                $user->givePermissionTo($val);
            }

            $data=array(
                'success'=>true,
                'pesan'=>'Permission berhasil disimpan',
                'error'=>''
            );
        }

        return $data;
    }

    public function hapus_role_user(Request $request){
        $rules=['permission'=>'required','user'=>'required'];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>'Validasi error',
                'error'=>''
            );
        }else{
            $user=User::find($request->input('user'));

            $permission=$request->input('permission');

            $user->revokePermissionTo($permission);

            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil dihapus',
                'error'=>''
            );
        }
        
        return $data;
    }

    public function change_password(Request $request){
        if($request->ajax()){
            $rules=[
                'current'=>'required',
                'password'=>'required',
                'password_confirmation'=>'required|same:password'
            ];

            $pesan=[
                'current.required'=>'Current password harus diisi',
                'password.required'=>'Password harus diisi',
                'password_confirmation.required'=>'Confirmasi password harus diisi'
            ];

            $validasi=\Validator::make($request->all(),$rules,$pesan);

            if($validasi->fails()){
                $data=array(
                    'success'=>false,
                    'pesan'=>'Validasi gagal',
                    'error'=>$validasi->errors()->all()
                );
            }else{
                if(\Hash::check($request->input('current'), \Auth::user()->password)){
                    $user=\App\User::find(auth()->user()->id);
                    $user->password=\Bcrypt($request->input('password'));
                    $user->save();

                    $data=array(
                        'success'=>true,
                        'pesan'=>'Password has been change',
                        'error'=>''
                    );

                    \Auth::logout();
                }else{
                    $data=array(
                        'success'=>false,
                        'pesan'=>'Current password wrong',
                        'error'=>''
                    );
                }
            }

            return $data;
        }
    }

    public function update_foto(Request $request)
    {
        $rules=['file'=>'required'];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>"Validasi Error",
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $user=User::find(auth()->user()->id);

            if($request->hasFile('file')){
                if(!is_dir('img/user/')){
                    mkdir('img/user/', 0777, TRUE);
                }

                $file=$request->file('file');
                $filename=str_random(5).'-'.$file->getClientOriginalName();
                $filename=$file->getClientOriginalName();
                $destinationPath="img/user/";

                if($file->move($destinationPath,$filename)){
                    $user->images=$filename;
                }
            }

            $user->save();

            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil diubah',
                'error'=>''
            );
        }

        return $data;
    }

    public function update_info(Request $request)
    {
        $rules=['desc'=>'required'];

        $validasi=\Validator::make($request->all(),$rules);

        if($validasi->fails()){
            $data=array(
                'success'=>false,
                'pesan'=>"Validasi Error",
                'errors'=>$validasi->errors()->all()
            );
        }else{
            $user=User::find(auth()->user()->id);
            $user->about=$request->input('desc');

            $user->save();

            $data=array(
                'success'=>true,
                'pesan'=>'Data berhasil diubah',
                'error'=>''
            );
        }

        return $data;
    }

    public function follower(User $user){
        $follower=auth()->user();

        if($follower->id==$user->id){
            return back()->withError("You can't follow yourselft");
        }

        if(!$follower->isFollowing($user->id)){
            $follower->follow($user->id);

            //sending notification
            $user->notify(new UserFollowed($follower));

            return back()->withSuccess("You are now friends with {$user->name}");
        }

        return back()->withError("You are already following {$user->name}");
    }

    public function unfollow(User $user){
        $follower=auth()->user();
        if($follower->isFollowing($user->id)){
            $follower->unfollow($user->id);
            return back()->withSuccess("You are not longer friends with {$user->name}");
        }

        return back()->withError("You are not following {$user->name}");
    }

    public function notifications(){
        return auth()->user()->unreadNotification()->limit(5)->aget()->toArray();
    }

    public function login_info(){
        $id=auth()->user()->id;

        $user=\App\User::with(
            [
                'perusahaan',
                'perusahaan.ket'
            ]
        )->find($id);

        return $user;
    }
}