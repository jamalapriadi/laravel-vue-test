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
    Route::get('update-customer','Master\CustomerController@update_customer');
    Route::get('list-keterangan','HomeController@list_keterangan');
    Route::post('keterangan','HomeController@keterangan');
    Route::resource('bank','Master\BankController');
    Route::get('list-bank','Master\BankController@list_bank');
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
    Route::post('cancel-order','Master\OrderController@cancel_order');
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
    Route::resource('storingretur','Master\StoringreturController');
    Route::resource('retur','Master\ReturController');

    /* user */
    Route::get('user','User\UserController@login_info');
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
    Route::get('cari-barang-by-nama-2','Master\BarangController@cari_barang_by_nama_2');
    Route::get('cari-customer-by-nama','Master\CustomerController@cari_customer_by_nama');
    Route::get('list-po-not-in-picking','Master\PoController@list_po_not_in_picking');
    Route::get('list-picking-not-in-order','Master\PickingController@list_picking_not_in_order');
    Route::get('stok-dirak','Master\StoringController@stok_di_rak');
    Route::get('po-by-id/{id}','Master\PoController@po_by_id');

    Route::resource('piutang','Master\PiutangController');
    Route::get('piutang-by-order/{id}','Master\PiutangController@piutang_by_order');
    Route::get('autonumber-piutang','Master\PiutangController@autonumber_piutang');
    Route::get('order-belum-lunas','Master\PiutangController@order_belum_lunas');
    Route::get('pilih-order-di-new-po/{id}','Master\PiutangController@pilih_order_di_new_po');

    Route::get('autonumber-retur','Master\ReturController@autonumber_retur');
    Route::get('tampil-hutang-customer','Master\PiutangController@tampil_hutang_customer');
    Route::get('order-by-id/{id}','Master\OrderController@order_by_id');
    Route::get('info-barang-by-order','Master\OrderController@info_barang_by_order');
    Route::get('list-order-client','Master\OrderController@list_order_client');
    Route::get('detail-order-by-id/{id}','Master\OrderController@detail_order_by_id');
    Route::get('cari-no-retur-by-id','Master\ReturController@cari_no_retur_by_id');
    Route::get('autonumber-storing-retur','Master\StoringreturController@autonumber_storing_retur');
    Route::get('list-retur-not-in-storing','Master\StoringreturController@list_retur_not_in_storing');
    Route::get('no-retur-by-id/{id}','Master\StoringreturController@no_retur_by_id');
    Route::get('rak-by-lokasi/{id}','Master\RakController@rak_by_lokasi');
    Route::get('order-jatuh-tempo','Master\OrderController@order_jatuh_tempo');
    Route::post('update-status-order/{id}','Master\OrderController@update_status_order');
    Route::get('sisa-hutang-customer/{id}','Master\OrderController@sisa_hutang_customer');

    Route::get('list-order-lunas','Master\OrderController@list_order_lunas');
    Route::get('list-order-hutang','Master\OrderController@list_order_hutang');
    Route::get('list-order-by-id/{id}','Master\OrderController@list_order_by_id');
    Route::get('lihat-stok','Master\StokController@lihat_stok');

    Route::get('autonumber-stok-opname','Master\StokopnameController@autonumber_stok_opname');
    Route::resource('stok-opname','Master\StokopnameController');
    Route::get('stok-opname-by-lokasi/{id}','Master\StokopnameController@stok_opname_by_lokasi');
    Route::get('get-rak-by-barang/{id}','Master\BarangController@get_rak_by_barang');
    Route::get('fifo-barang/{id}','Master\BarangController@fifo_barang');
    Route::get('customer-not-in-picking','Master\CustomerController@customer_not_in_picking');
    Route::get('po-by-customer/{id}','Master\PoController@po_by_customer');
    Route::resource('jenis-customer','Master\JeniscustomerController');
    Route::get('list-jenis-customer','Master\JeniscustomerController@list_jenis_customer');
    Route::get('print-order/{id}','Master\OrderController@print_order');

    Route::group(['prefix'=>'laporan'],function(){
        Route::get('penjualan','Master\LaporanController@laporan_penjualan');
        Route::get('per-sales','Master\LaporanController@laporan_penjualan_per_sales');
        Route::get('stok','Master\LaporanController@laporan_stok');
    });
});

Route::get('cek-duplicate-po',function(){
    $lis=array(
        array(
            'dos'=> 3,
            'kd_barang'=> "BRG003381",
            'nama_rak'=> "Z002",
            'nm_barang'=> "RITCHI ASORTED 12X230GR",
            'pcs'=> 2,
            'rak'=> 573,
            'total_pcs'=> 5,
            'yg_diminta'=> 5,
        ),
        array(
            'dos'=> 2,
            'kd_barang'=> "BRG003383",
            'nama_rak'=> "Z002",
            'nm_barang'=> "RITCHI COKELAT 12X230GR",
            'pcs'=> 6,
            'rak'=> 573,
            'total_pcs'=> 8,
            'yg_diminta'=> 8,
        ),
        array(
            'dos'=> 7,
            'kd_barang'=> "BRG003383",
            'nama_rak'=> "Z002",
            'nm_barang'=> "RITCHI COKELAT 12X230GR",
            'pcs'=> 2,
            'rak'=> 573,
            'total_pcs'=> 9,
            'yg_diminta'=> 9,
        )
    );

    $data_summ=array();
    foreach($lis as $value){
        $data_summ[$value['kd_barang'].'-'.$value['nama_rak']]=array(
            'kd_barang'=>'',
            'rak'=>'',
            'jumlah'=>0
        );
    }

    
    foreach($lis as $value){
        $data_summ[$value['kd_barang'].'-'.$value['nama_rak']]['kd_barang']=$value['kd_barang'];
        $data_summ[$value['kd_barang'].'-'.$value['nama_rak']]['rak']=$value['rak'];
        $data_summ[$value['kd_barang'].'-'.$value['nama_rak']]['jumlah']+=$value['total_pcs'];
    }
    return $data_summ;

    // foreach($lis as $key=>$val){
    //     $data_summ[$key]=array(
    //         'kd_barang'=>$val['kd_barang'],
    //         'rak'=>$val['rak'],
    //         'total_pcs'=>$val['total_pcs']
    //     );
    // }

    // $hasil=array();
    // foreach($lis as $key=>$val){
    //     for($a=0; $a<count($data_summ); $a++){
            
    //     }
    // }

    return $data_summ;
});