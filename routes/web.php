<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix'=>'data','middleware'=>'auth'],function(){
    Route::resource('bank','Master\BankController');
    Route::resource('barang','Master\BarangController');
    Route::resource('customer','Master\CustomerController');
    Route::resource('ket','Master\KetController');
    Route::resource('kelompok','Master\KelompokController');
    Route::resource('kota','Master\KotaController');
    Route::resource('lokasi','Master\LokasiController');
    Route::resource('merk','Master\MerkController');
    Route::resource('perusahaan','Master\PerusahaanController');
    Route::resource('mutasi','Master\MutasiController');
    Route::resource('order','Master\OrderController');
    Route::resource('picking','Master\PickingController');
    Route::resource('po','Master\PoController');
    Route::resource('program','Master\ProgramController');
    Route::resource('rak','Master\RakController');
    Route::resource('sales','Master\SalesController');
    Route::resource('stok','Master\StokController');
    Route::resource('rak','Master\RakController');
    Route::resource('suplier','Master\SuplierController');
    Route::resource('terima','Master\TerimaController');

    /* user */
    Route::resource('users','User\UserController');
    Route::post('update-foto','User\UserController@update_foto');
    Route::post('update-info','User\UserController@update_info');
    Route::resource('roles','User\RoleController');
    Route::resource('permissions','User\PermissionController');
    Route::get('list-role-with-permission/{id}','User\RoleController@list_role_with_permission');
    Route::get('list-role-user','User\UserController@list_role');
    Route::post('save-role-user','User\UserController@save_role_user');
    Route::post('hapus-role-user','User\UserController@hapus_role_user');
    Route::post('change-password','User\UserController@change_password');
    /* end user */

    Route::get('list-perusahaan','Master\PerusahaanController@list_perusahaan');
    Route::get('list-kota','Master\KotaController@list_kota');
    Route::get('list-kelompok','Master\KelompokController@list_kelompok');
    Route::get('list-merk','Master\MerkController@list_merk');
    Route::get('autonumber-barang','Master\BarangController@autonumber_barang');
});