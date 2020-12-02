<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['middleware'=>'guest'], function()
{
  Route::get('/login', 'MainController@login')->name('login');
  Route::post('/login', 'MainController@loginProcess')->name('login.process');
});

Route::group(['middleware'=>'auth'], function()
{
  Route::get('/', 'MainController@index')->name('index')->middleware('role:admin');
  Route::get('/keluar', 'MainController@logout')->name('logout');
  Route::get('/profil', 'MainController@profile')->name('profile');
  Route::post('/profil', 'MainController@changeProfile')->name('changeProfile');

  Route::group(['prefix'=>'kategori','middleware'=>'role:admin'], function()
  {
    Route::get('/', 'KategoriController@index')->name('kategori.index');
    Route::get('/tambah', 'KategoriController@create')->name('kategori.create');
    Route::post('/tambah', 'KategoriController@store')->name('kategori.store');
    Route::get('/{uuid}/ubah', 'KategoriController@edit')->name('kategori.edit');
    Route::post('/{uuid}/ubah', 'KategoriController@update')->name('kategori.update');
    Route::get('/{uuid}/hapus', 'KategoriController@destroy')->name('kategori.destroy');
  });

  Route::group(['prefix'=>'barang','middleware'=>'role:admin,stoker'], function()
  {
    Route::get('/', 'BarangController@index')->name('barang.index');
    Route::get('/tambah', 'BarangController@create')->name('barang.create');
    Route::post('/tambah', 'BarangController@store')->name('barang.store');
    Route::get('/{uuid}', 'BarangController@show')->name('barang.show');
    Route::get('/{uuid}/ubah', 'BarangController@edit')->name('barang.edit');
    Route::post('/{uuid}/ubah', 'BarangController@update')->name('barang.update');
    Route::get('/{uuid}/hapus', 'BarangController@destroy')->name('barang.destroy');
  });

  Route::group(['prefix'=>'transaksi'], function()
  {
    Route::get('/', 'TransaksiController@index')->name('transaksi.index')->middleware('role:admin,cashier');
    Route::get('/tambah', 'TransaksiController@create')->name('transaksi.create')->middleware('role:admin,cashier');
    Route::post('/tambah', 'TransaksiController@store')->name('transaksi.store')->middleware('role:admin,cashier');
    Route::get('/{uuid}', 'TransaksiController@show')->name('transaksi.show')->middleware('role:admin');
    Route::get('/{uuid}/cetak', 'TransaksiController@print')->name('transaksi.print')->middleware('role:admin,cashier');
    Route::get('/{uuid}/ubah', 'TransaksiController@edit')->name('transaksi.edit')->middleware('role:admin');
    Route::post('/{uuid}/ubah', 'TransaksiController@update')->name('transaksi.update')->middleware('role:admin');
    Route::get('/{uuid}/hapus', 'TransaksiController@destroy')->name('transaksi.destroy')->middleware('role:admin');
  });

  Route::group(['prefix'=>'rekapitulasi','middleware'=>'role:admin'], function()
  {
    Route::get('/', 'RekapitulasiController@index')->name('rekapitulasi.index');
    Route::post('/', 'RekapitulasiController@rekap')->name('rekapitulasi.rekap');
    Route::post('/rekap-print', 'RekapitulasiController@print')->name('rekapitulasi.print');
  });

  Route::group(['prefix'=>'user','middleware'=>'role:admin'], function()
  {
    Route::get('/', 'UserController@index')->name('user.index');
    Route::get('/tambah', 'UserController@create')->name('user.create');
    Route::post('/tambah', 'UserController@store')->name('user.store');
    Route::get('/{uuid}/ubah', 'UserController@edit')->name('user.edit');
    Route::post('/{uuid}/ubah', 'UserController@update')->name('user.update');
    Route::get('/{uuid}/hapus', 'UserController@destroy')->name('user.destroy');
  });

});
