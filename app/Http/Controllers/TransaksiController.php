<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\Penjualan;
use Validator;
use Str;
use Carbon\Carbon;

class TransaksiController extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function index(Request $r)
  {
    $query = Transaksi::orderBy('tgl_transaksi','desc')
    ->orderBy('nama_transaksi','asc')
    ->when(!auth()->user()->isAdmin,function($q){
      $q->where('user_id',auth()->user()->id);
    })
    ->when($r->cari,function($q,$role){
      $q->where('kode_transaksi','like',"%$role%")
      ->orWhere('nama_transaksi','like',"%$role%")
      ->orWhere('tgl_transaksi','like',"%$role%")
      ->orWhere('user_name','like',"%$role%")
      ->orWhereHas('penjualan',function($q) use($role){
        $q->where('kode','like',"%$role%")
        ->orWhere('harga_modal','like',"%$role%")
        ->orWhere('harga_jual','like',"%$role%")
        ->orWhere('nama','like',"%$role%");
      });
    })
    ->paginate(10)
    ->appends(['cari'=>$r->cari]);
    $data = [
      'title' => 'Transaksi',
      'data' => $query,
    ];

    return view('transaksi.index',$data);
  }

  public function create(Request $r)
  {
    if ($r->ajax()) {
      $barang = Barang::where('kode',$r->kode)
      ->orWhere('nama','like',"%$r->kode%")
      ->first();
      return response()->json([
        'status'=>'success',
        'data'=>$barang
      ]);
    }
    $now = Carbon::now();
    $kode_transaksi = 'TR-'.($now->timestamp);
    $nama_transaksi = 'Transaksi '.($now->format('d/m/Y H:i:s'));
    $data = [
      'title' => 'Transaksi Baru',
      'tgl_transaksi' => $now->format('d/m/Y'),
      'kode_transaksi' => $kode_transaksi,
      'nama_transaksi' => $nama_transaksi,
    ];

    return view('transaksi.create',$data);
  }

  public function store(Request $r)
  {
    $roles = [
      'tgl_transaksi' => 'required',
      'kode_transaksi' => 'required|unique:transaksi',
      'dibayar' => 'required',
      'diskon' => 'required',
      'id' => 'required',
      'jumlah' => 'required',
    ];
    $messages = [
      'tgl_transaksi.required' => 'Tanggal transaksi tidak boleh kosong',
      'kode_transaksi.required' => 'Kode transaksi tidak boleh kosong',
      'kode_transaksi.unique' => 'Kode transaksi telah digunakan',
      'dibayar.required' => ':Attribute tidak boleh kosong',
      'diskon.required' => ':Attribute tidak boleh kosong',
      'id.required' => 'Barang tidak boleh kosong',
      'jumlah.required' => 'Barang tidak boleh kosong',
    ];

    Validator::make($r->all(),$roles,$messages)->validate();

    foreach ($r->id as $key => $id) {
      $jl = Barang::find($id);
      if ($jl->jumlah < $r->jumlah[$key]) {
        return redirect()->back()->withErrors('Jumlah barang '.$jl->nama.' melebihi stok yang tersedia!');
      }
    }

    $insert = new Transaksi;
    $insert->uuid = (string) Str::uuid();
    $insert->kode_transaksi = $r->kode_transaksi;
    $insert->nama_transaksi = $r->nama_transaksi;
    $insert->tgl_transaksi = $r->tgl_transaksi;
    $insert->diskon = $r->diskon;
    $insert->dibayar = $r->dibayar;
    $insert->user_id = auth()->user()->id;
    $insert->user_name = auth()->user()->name;

    if ($insert->save()) {
      $bi = 0;
      foreach ($r->id as $key => $id) {
        $barang = Barang::find($id);
        if ($barang) {
          $bi++;
          $pb = new Penjualan;
          $pb->uuid = (string) Str::uuid();
          $pb->transaksi_id = $insert->id;
          $pb->barang_id = $barang->id;
          $pb->kode = $barang->kode;
          $pb->nama = $barang->nama;
          $pb->harga_modal = $barang->harga_modal;
          $pb->harga_jual = $barang->harga_jual;
          $pb->jumlah = $r->jumlah[$key];
          $pb->save();
          $barang->jumlah -= $r->jumlah[$key];
          $barang->save();
        }
      }
      if ($bi==0) {
        $insert->delete();
      }
      return redirect()->route('transaksi.index')->with('message','Transaksi berhasil disimpan')->with('print',$insert->uuid);
    }
    return redirect()->back()->withErrors('Transaksi gagal disimpan');
  }

  public function show($uuid)
  {
    $query = Transaksi::where('uuid',$uuid)->first();
    if (!$query) {
      return redirect()->route('transaksi.index')->withErrors('Data tidak tersedia');
    }
    $data = [
      'title' => 'Detail Transaksi - '.$query->nama_transaksi,
      'data' => $query
    ];

    return view('transaksi.show',$data);
  }

  public function edit($uuid,Request $r)
  {
    if ($r->ajax()) {
      $barang = Barang::where('kode',$r->kode)
      ->orWhere('nama','like',"%$r->kode%")
      ->first();
      return response()->json([
        'status'=>'success',
        'data'=>$barang
      ]);
    }
    $query = Transaksi::with(['penjualan'=>function($q){
      $q->orderBy('kode','asc')
      ->orderBy('nama','asc');
    }])
    ->where('uuid',$uuid)->first();
    if (!$query) {
      return redirect()->route('transaksi.index')->withErrors('Data tidak tersedia');
    }
    $data = [
      'title' => 'Ubah Transaksi - '.$query->nama_transaksi,
      'data' => $query
    ];

    return view('transaksi.edit',$data);
  }

  public function update($uuid,Request $r)
  {
    $roles = [
      'tgl_transaksi' => 'required',
      'kode_transaksi' => 'required|unique:transaksi,kode_transaksi,'.$uuid.',uuid',
      'dibayar' => 'required',
      'diskon' => 'required',
      'id' => 'required',
      'jumlah' => 'required',
    ];
    $messages = [
      'tgl_transaksi.required' => 'Tanggal transaksi tidak boleh kosong',
      'kode_transaksi.required' => 'Kode transaksi tidak boleh kosong',
      'kode_transaksi.unique' => 'Kode transaksi telah digunakan',
      'dibayar.required' => ':Attribute tidak boleh kosong',
      'diskon.required' => ':Attribute tidak boleh kosong',
      'id.required' => 'Barang tidak boleh kosong',
      'jumlah.required' => 'Barang tidak boleh kosong',
    ];

    Validator::make($r->all(),$roles,$messages)->validate();

    $insert = Transaksi::where('uuid',$uuid)->first();
    if (!$insert) {
      return redirect()->route('transaksi.index')->withErrors('Data tidak tersedia');
    }
    $insert->kode_transaksi = $r->kode_transaksi;
    $insert->nama_transaksi = $r->nama_transaksi;
    $insert->tgl_transaksi = $r->tgl_transaksi;
    $insert->diskon = $r->diskon;
    $insert->dibayar = $r->dibayar;
    $insert->user_id = auth()->user()->id;
    $insert->user_name = auth()->user()->name;

    if ($insert->save()) {
      $bi = 0;
      foreach ($insert->penjualan as $key => $pj) {
        $barang = $pj->barangByKode;
        $barang->jumlah += $pj->jumlah;
        $barang->save();
        $pj->delete();
      }
      foreach ($r->id as $key => $id) {
        $barang = Barang::find($id);
        if ($barang) {
          $bi++;
          $pb = new Penjualan;
          $pb->uuid = (string) Str::uuid();
          $pb->transaksi_id = $insert->id;
          $pb->barang_id = $barang->id;
          $pb->kode = $barang->kode;
          $pb->nama = $barang->nama;
          $pb->harga_modal = $barang->harga_modal;
          $pb->harga_jual = $barang->harga_jual;
          $pb->jumlah = $r->jumlah[$key];
          $pb->save();
          $barang->jumlah -= $r->jumlah[$key];
          $barang->save();
        }
      }
      if ($bi==0) {
        $insert->delete();
      }
      return redirect()->route('transaksi.index')->with('message','Transaksi berhasil diubah');
    }
    return redirect()->back()->withErrors('Transaksi gagal disimpan');
  }

  public function destroy($uuid)
  {
    $transaksi = Transaksi::where('uuid',$uuid)->first();
    foreach ($transaksi->penjualan as $key => $pj) {
      $barang = $pj->barangByKode;
      $barang->jumlah += $pj->jumlah;
      $barang->save();
      $pj->delete();
    }
    if ($transaksi->delete()) {
      return redirect()->route('transaksi.index')->with('message','Transaksi berhasil dihapus');
    }
  }

  public function print($uuid)
  {
    $query = Transaksi::where('uuid',$uuid)->first();
    if (!$query) {
      return response()->json([
        'status' => 'error',
        'message' => 'Transaksi tidak tersedia',
      ]);
    }

    $html = view('transaksi.print',['data'=>$query])->render();

    return response()->json([
      'status' => 'success',
      'html' => $html,
    ]);
  }

}
