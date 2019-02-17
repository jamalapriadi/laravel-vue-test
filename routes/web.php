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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix'=>'data','middleware'=>'auth'],function(){
    Route::resource('bank','Master\BankController');
    Route::resource('barang','Master\BarangController');
    Route::get('sample-barang','Master\BarangController@sample_barang');
    Route::post('import-new-barang','Master\BarangController@import_new_barang');
    Route::post('import-update-barang','Master\BarangController@import_update_barang');
    Route::get('export-barang','Master\BarangController@export_barang');
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
    Route::get('sample-rak','Master\RakController@sample_rak');
    Route::post('import-new-rak','Master\RakController@import_new_rak');
    Route::resource('sales','Master\SalesController');
    Route::resource('stok','Master\StokController');
    // Route::resource('rak','Master\RakController');
    Route::resource('suplier','Master\SuplierController');
    Route::resource('terima','Master\TerimaController');
    Route::resource('storing','Master\StoringController');

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
    Route::get('list-lokasi','Master\LokasiController@list_lokasi');
    Route::get('list-customer','Master\CustomerController@list_customer');
    Route::get('list-rak','Master\RakController@list_rak');
    Route::get('list-sales','Master\SalesController@list_sales');
    Route::get('autonumber-barang','Master\BarangController@autonumber_barang');
    Route::get('autonumber-order','Master\OrderController@autonumber_order');
    Route::get('autonumber-customer','Master\CustomerController@autonumber_customer');
    Route::get('autonumber-program','Master\ProgramController@autonumber_program');
    Route::get('autonumber-po','Master\PoController@autonumber_po');
    Route::get('autonumber-picking','Master\PickingController@autonumber_picking');
    Route::get('autonumber-order','Master\OrderController@autonumber_order');
    Route::get('autonumber-storing','Master\StoringController@autonumber_storing');
    Route::get('autonumber-mutasi','Master\MutasiController@autonumber_mutasi');
    Route::get('list-barang','Master\BarangController@list_barang');
    Route::get('cari-barang-by-kode/{id}','Master\BarangController@cari_barang_by_kode');
    Route::get('cari-barang-by-nama','Master\BarangController@cari_barang_by_nama');
    Route::get('cari-customer-by-nama','Master\CustomerController@cari_customer_by_nama');
    Route::get('list-po-not-in-picking','Master\PoController@list_po_not_in_picking');
    Route::get('list-picking-not-in-order','Master\PickingController@list_picking_not_in_order');
    Route::get('stok-dirak','Master\StoringController@stok_di_rak');
    Route::get('po-by-id/{id}','Master\PoController@po_by_id');
});