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

class RekapitulasiController extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function index(Request $r)
  {
    $data = [
      'title' => 'Rekapitulasi',
    ];

    return view('rekapitulasi.index',$data);
  }

  public function rekap(Request $r)
  {
    $dates = explode(" - ",$r->daterange);
    $start = Carbon::createFromFormat('d/m/Y',$dates[0]);
    $end = Carbon::createFromFormat('d/m/Y',$dates[1]);

    $transaksi = Transaksi::where('created_at','>=',$start->startOfDay()->toDateTimeString())
    ->where('created_at','<=',$end->endOfDay()->toDateTimeString())
    ->with('penjualan')
    ->get();

    $render = view('rekapitulasi.table',[
      'data'=>$transaksi,
      'start'=>$start,
      'end'=>$end,
    ])->render();

    return response()->json([
      'status'=>'success',
      'html'=>$render,
    ]);
  }

  public function print(Request $r)
  {
    $dates = explode(" - ",$r->daterange);
    $start = Carbon::createFromFormat('d/m/Y',$dates[0]);
    $end = Carbon::createFromFormat('d/m/Y',$dates[1]);

    $transaksi = Transaksi::where('created_at','>=',$start->startOfDay()->toDateTimeString())
    ->where('created_at','<=',$end->endOfDay()->toDateTimeString())
    ->with('penjualan')
    ->get();
    if (!count($transaksi)) {
      return response()->json([
        'status' => 'error',
        'message' => 'Rekapitulasi tidak tersedia',
      ]);
    }

    $render = view('rekapitulasi.print',[
      'data'=>$transaksi,
      'start'=>$start,
      'end'=>$end,
    ])->render();

    return response()->json([
      'status' => 'success',
      'html' => $render,
    ]);
  }

}
