<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

use App\Models\User;
use App\Models\Transaksi;
use App\Models\Barang;
use App\Models\Kategori;
use Carbon\Carbon;
use App\Helper;
use Validator;

class MainController extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function login()
  {
    $data = [
      'title' => 'Halaman Login'
    ];

    return view('login',$data);
  }

  public function loginProcess(Request $r)
  {
    $roles = [
      'username' => 'required',
      'password' => 'required',
    ];
    $messages = [
      'username.required' => ':Attribute harus diisi',
      'password.required' => ':Attribute harus diisi',
    ];

    Validator::make($r->all(),$roles,$messages)->validate();

    $auth = auth()->attempt([
      'username' => $r->username,
      'password' => $r->password,
    ],($r->remember=='on'?true:false));

    if ($auth) {
      return redirect()->route('index');
    }
    return redirect()->back()->withErrors(['Login tidak benar!']);
  }

  public function index()
  {
    $now = Carbon::now();

    $users = User::count();
    $barang = Barang::count();
    $kategori = Kategori::count();

    $trDay = Transaksi::where('created_at','>=',$now->startOfDay()->toDateTimeString())
    ->where('created_at','<=',$now->endOfDay()->toDateTimeString())->with('penjualan')->get();
    $trWeek = Transaksi::where('created_at','>=',$now->startOfWeek()->toDateTimeString())
    ->where('created_at','<=',$now->endOfWeek()->toDateTimeString())->with('penjualan')->get();
    $trMonth = Transaksi::where('created_at','>=',$now->startOfMonth()->toDateTimeString())
    ->where('created_at','<=',$now->endOfMonth()->toDateTimeString())->with('penjualan')->get();

    $lbDay = 0;
    $lbWeek = 0;
    $lbMonth = 0;
    $lbTotal = 0;

    foreach ($trDay as $key => $v) {
      $lbDay += $v->laba;
    }
    foreach ($trWeek as $key => $v) {
      $lbWeek += $v->laba;
    }
    foreach ($trMonth as $key => $v) {
      $lbMonth += $v->laba;
    }

    $data = [
      'title' => 'Beranda',
      'trDay' => count($trDay),
      'trWeek' => count($trWeek),
      'trMonth' => count($trMonth),
      'lbDay' => 'Rp '.number_format($lbDay,0,'','.'),
      'lbWeek' => 'Rp '.number_format($lbWeek,0,'','.'),
      'lbMonth' => 'Rp '.number_format($lbMonth,0,'','.'),
      'users' => $users,
      'barang' => $barang,
      'kategori' => $kategori,
    ];

    return view('index',$data);
  }

  public function profile()
  {
    $helper = new Helper;
    $ipv4 = $helper->getServerIP();
    $user = auth()->user();
    $data = [
      'title' => 'Halaman Profil',
      'data' => $user,
      'ipv4' => $ipv4,
    ];

    return view('profile',$data);
  }

  public function changeProfile(Request $r)
  {
    $roles = [
      'name' => 'required',
    ];
    $messages = [
      'name.required' => 'Nama tidak boleh kosong',
    ];

    Validator::make($r->all(),$roles,$messages)->validate();

    $insert = auth()->user();
    if (!$insert) {
      return redirect()->route('profil')->withErrors('Data tidak ditemukan');
    }

    $insert->name = $r->name;
    if ($r->password!='') {
      $insert->password = $r->password;
    }

    if ($insert->save()) {
      return redirect()->route('profile')->with('message','Data berhasil disimpan');
    }
    return redirect()->route('profile')->withErrors('Terjadi kesalahan sistem, data gagal disimpan');
  }

  public function logout()
  {
    auth()->logout();
    return redirect()->route('login');
  }
}
