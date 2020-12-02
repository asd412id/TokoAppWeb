<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\User;
use Validator;
use Str;

class UserController extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function index(Request $r)
  {
    $query = User::orderBy('name','asc')
    ->where('id','!=',auth()->user()->id)
    ->when($r->cari,function($q,$role){
      $q->where('username','like',"%$role%")
      ->orWhere('name','like',"%$role%")
      ->orWhere('role','like',"%$role%");
    })
    ->paginate(10)
    ->appends(['cari'=>$r->cari]);
    $data = [
      'title' => 'Daftar User',
      'data' => $query,
    ];

    return view('user.index',$data);
  }

  public function create()
  {
    $data = [
      'title' => 'Tambah User',
    ];

    return view('user.create',$data);
  }

  public function store(Request $r)
  {
    $roles = [
      'username' => 'required|unique:users',
      'name' => 'required',
      'password' => 'required',
      'role' => 'required',
    ];
    $messages = [
      'username.required' => ':Attribute tidak boleh kosong',
      'username.unique' => ':Attribute :input sudah digunakan',
      'name.required' => 'Nama tidak boleh kosong',
      'password.required' => ':Attribute tidak boleh kosong',
      'role.required' => ':Attribute tidak boleh kosong',
    ];

    Validator::make($r->all(),$roles,$messages)->validate();

    if (!in_array($r->role,['cashier','stoker','admin'])) {
      return redirect()->route('user.index')->withErrors('Role tidak tersedia');
    }

    $insert = new User;
    $insert->uuid = (string) Str::uuid();
    $insert->username = $r->username;
    $insert->name = $r->name;
    $insert->password = $r->password;
    $insert->role = $r->role;

    if ($insert->save()) {
      return redirect()->route('user.index')->with('message','Data berhasil disimpan');
    }
    return redirect()->route('user.index')->withErrors('Terjadi kesalahan sistem, data gagal disimpan');
  }

  public function edit($uuid)
  {
    $query = User::where('uuid',$uuid)->first();
    if (!$query) {
      return redirect()->route('user.index')->withErrors('Data tidak ditemukan');
    }
    $data = [
      'title' => 'Edit User',
      'data' => $query
    ];

    return view('user.edit',$data);
  }

  public function update($uuid,Request $r)
  {
    $roles = [
      'username' => 'required|unique:users,username,'.$uuid.',uuid',
      'name' => 'required',
      'role' => 'required',
    ];
    $messages = [
      'username.required' => ':Attribute tidak boleh kosong',
      'username.unique' => ':Attribute :input sudah digunakan',
      'name.required' => 'Nama tidak boleh kosong',
      'role.required' => ':Attribute tidak boleh kosong',
    ];

    Validator::make($r->all(),$roles,$messages)->validate();

    $insert = User::where('uuid',$uuid)->first();
    if (!$insert) {
      return redirect()->route('user.index')->withErrors('Data tidak ditemukan');
    }

    if (!in_array($r->role,['cashier','stoker','admin'])) {
      return redirect()->route('user.index')->withErrors('Role tidak tersedia');
    }

    $insert->username = $r->username;
    $insert->name = $r->name;
    if ($r->password!='') {
      $insert->password = $r->password;
    }
    $insert->role = $r->role;

    if ($insert->save()) {
      return redirect()->route('user.index')->with('message','Data berhasil disimpan');
    }
    return redirect()->route('user.index')->withErrors('Terjadi kesalahan sistem, data gagal disimpan');
  }

  public function destroy($uuid)
  {
    $query = User::where('uuid',$uuid)->first();
    if (!$query) {
      return redirect()->route('user.index')->withErrors('Data tidak ditemukan');
    }
    $query->barang()->detach();
    if ($query->delete()) {
      return redirect()->route('user.index')->with('message','Data berhasil dihapus');
    }
    return redirect()->route('user.index')->withErrors('Terjadi kesalahan sistem, data gagal dihapus');
  }

}
