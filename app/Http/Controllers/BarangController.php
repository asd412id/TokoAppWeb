<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Barang;
use App\Models\Kategori;
use Validator;
use Str;

class BarangController extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function index(Request $r)
  {
    $query = Barang::with('kategori')
    ->orderBy('kode','asc')
    ->when($r->cari,function($q,$role){
      $q->where('kode','like',"%$role%")
      ->orWhere('harga_modal','like',"%$role%")
      ->orWhere('harga_jual','like',"%$role%")
      ->orWhere('nama','like',"%$role%")
      ->orWhereHas('kategori',function($q) use($role){
        $q->where('nama','like',"%$role%")
        ->orWhere('keterangan','like',"%$role%");
      });
    })
    ->paginate(10)
    ->appends(['cari'=>$r->cari]);
    $data = [
      'title' => 'Daftar Barang',
      'data' => $query,
    ];

    return view('barang.index',$data);
  }

  public function create()
  {
    $data = [
      'title' => 'Tambah Barang',
      'kategori' => Kategori::orderBy('nama','asc')->get()
    ];

    return view('barang.create',$data);
  }

  public function store(Request $r)
  {
    $roles = [
      'kode' => 'required|unique:barang',
      'nama' => 'required',
      'harga_modal' => 'required',
      'harga_jual' => 'required',
      'jumlah' => 'required',
    ];
    $messages = [
      'kode.required' => ':Attribute Barang tidak boleh kosong',
      'kode.unique' => 'Barang dengan kode :input sudah tersedia',
      'nama.required' => ':Attribute Barang tidak boleh kosong',
      'harga_modal.required' => ':Attribute Barang tidak boleh kosong',
      'harga_jual.required' => ':Attribute Barang tidak boleh kosong',
      'jumlah.required' => ':Attribute Barang tidak boleh kosong',
    ];

    Validator::make($r->all(),$roles,$messages)->validate();

    $user = auth()->user();
    $insert = new Barang;
    $insert->uuid = (string) Str::uuid();
    $insert->kode = $r->kode;
    $insert->nama = $r->nama;
    $insert->harga_modal = $r->harga_modal;
    $insert->harga_jual = $r->harga_jual;
    $insert->jumlah = $r->jumlah;
    $insert->user_id = $user->id;
    $insert->user_name = $user->name;

    if ($insert->save()) {
      $insert->kategori()->attach($r->kategori);
      return redirect()->route('barang.index')->with('message','Data berhasil disimpan');
    }
    return redirect()->route('barang.index')->withErrors('Terjadi kesalahan sistem, data gagal disimpan');
  }

  public function show($uuid)
  {
    $query = Barang::where('uuid',$uuid)->first();
    if (!$query) {
      return redirect()->route('transaksi.index')->withErrors('Data tidak tersedia');
    }
    $data = [
      'title' => 'Detail Barang - '.$query->nama,
      'data' => $query
    ];

    return view('barang.show',$data);
  }

  public function edit($uuid)
  {
    $query = Barang::where('uuid',$uuid)->first();
    if (!$query) {
      return redirect()->route('barang.index')->withErrors('Data tidak ditemukan');
    }
    $data = [
      'title' => 'Ubah Data Barang',
      'kategori' => Kategori::orderBy('nama','asc')->get(),
      'data' => $query
    ];

    return view('barang.edit',$data);
  }

  public function update($uuid,Request $r)
  {
    $roles = [
      'kode' => 'required|unique:barang,kode,'.$uuid.',uuid',
      'nama' => 'required',
      'harga_modal' => 'required',
      'harga_jual' => 'required',
      'jumlah' => 'required',
    ];
    $messages = [
      'kode.required' => ':Attribute Barang tidak boleh kosong',
      'kode.unique' => 'Barang dengan kode :input sudah tersedia',
      'nama.required' => ':Attribute Barang tidak boleh kosong',
      'harga_modal.required' => ':Attribute Barang tidak boleh kosong',
      'harga_jual.required' => ':Attribute Barang tidak boleh kosong',
      'jumlah.required' => ':Attribute Barang tidak boleh kosong',
    ];

    Validator::make($r->all(),$roles,$messages)->validate();

    $user = auth()->user();
    $insert = Barang::where('uuid',$uuid)->first();
    if (!$insert) {
      return redirect()->route('barang.index')->withErrors('Data tidak ditemukan');
    }
    $insert->kode = $r->kode;
    $insert->nama = $r->nama;
    $insert->harga_modal = $r->harga_modal;
    $insert->harga_jual = $r->harga_jual;
    $insert->jumlah = $r->jumlah;
    $insert->user_id = $user->id;
    $insert->user_name = $user->name;

    if ($insert->save()) {
      $insert->kategori()->sync($r->kategori);
      return redirect()->route('barang.index')->with('message','Data berhasil disimpan');
    }
    return redirect()->route('barang.index')->withErrors('Terjadi kesalahan sistem, data gagal disimpan');
  }

  public function destroy($uuid)
  {
    $query = Barang::where('uuid',$uuid)->first();
    if (!$query) {
      return redirect()->route('barang.index')->withErrors('Data tidak ditemukan');
    }
    $query->kategori()->detach();
    if ($query->delete()) {
      return redirect()->route('barang.index')->with('message','Data berhasil dihapus');
    }
    return redirect()->route('barang.index')->withErrors('Terjadi kesalahan sistem, data gagal dihapus');
  }

}
