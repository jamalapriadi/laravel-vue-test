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
});