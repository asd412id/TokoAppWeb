<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\Kategori;
use Validator;
use Str;

class KategoriController extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function index(Request $r)
  {
    $query = Kategori::orderBy('nama','asc')
    ->when($r->cari,function($q,$role){
      $q->where('nama','like',"%$role%")
      ->orWhere('keterangan','like',"%$role%");
    })
    ->paginate(10)
    ->appends(['cari'=>$r->cari]);
    $data = [
      'title' => 'Kategori Barang',
      'data' => $query,
    ];

    return view('kategori.index',$data);
  }

  public function create()
  {
    $data = [
      'title' => 'Tambah Kategori Barang',
    ];

    return view('kategori.create',$data);
  }

  public function store(Request $r)
  {
    $roles = [
      'nama' => 'required|unique:kategori',
    ];
    $messages = [
      'nama.required' => ':Attribute Kategori tidak boleh kosong',
      'nama.unique' => 'Kategori dengan nama :input sudah tersedia',
    ];

    Validator::make($r->all(),$roles,$messages)->validate();

    $insert = new Kategori;
    $insert->uuid = (string) Str::uuid();
    $insert->nama = $r->nama;
    $insert->keterangan = $r->keterangan;

    if ($insert->save()) {
      return redirect()->route('kategori.index')->with('message','Data berhasil disimpan');
    }
    return redirect()->route('kategori.index')->withErrors('Terjadi kesalahan sistem, data gagal disimpan');
  }

  public function edit($uuid)
  {
    $query = Kategori::where('uuid',$uuid)->first();
    if (!$query) {
      return redirect()->route('kategori.index')->withErrors('Data tidak ditemukan');
    }
    $data = [
      'title' => 'Edit Kategori Barang',
      'data' => $query
    ];

    return view('kategori.edit',$data);
  }

  public function update($uuid,Request $r)
  {
    $roles = [
      'nama' => 'required|unique:kategori,nama,'.$uuid.',uuid',
    ];
    $messages = [
      'nama.required' => ':Attribute Kategori tidak boleh kosong',
      'nama.unique' => 'Kategori dengan nama :input sudah tersedia',
    ];

    Validator::make($r->all(),$roles,$messages)->validate();

    $insert = Kategori::where('uuid',$uuid)->first();
    if (!$insert) {
      return redirect()->route('kategori.index')->withErrors('Data tidak ditemukan');
    }
    $insert->nama = $r->nama;
    $insert->keterangan = $r->keterangan;

    if ($insert->save()) {
      return redirect()->route('kategori.index')->with('message','Data berhasil disimpan');
    }
    return redirect()->route('kategori.index')->withErrors('Terjadi kesalahan sistem, data gagal disimpan');
  }

  public function destroy($uuid)
  {
    $query = Kategori::where('uuid',$uuid)->first();
    if (!$query) {
      return redirect()->route('kategori.index')->withErrors('Data tidak ditemukan');
    }
    $query->barang()->detach();
    if ($query->delete()) {
      return redirect()->route('kategori.index')->with('message','Data berhasil dihapus');
    }
    return redirect()->route('kategori.index')->withErrors('Terjadi kesalahan sistem, data gagal dihapus');
  }

}
