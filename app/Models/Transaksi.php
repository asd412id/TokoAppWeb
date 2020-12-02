<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
  protected $table = 'transaksi';
  protected $dates = [
    'created_at',
    'updated_at',
    'tgl_transaksi',
  ];

  public function penjualan()
  {
    return $this->hasMany(Penjualan::class);
  }

  public function getTglTransaksiLocalAttribute()
  {
    return $this->tgl_transaksi->locale('id')->translatedFormat('j F Y');
  }

  public function setTglTransaksiAttribute($value='')
  {
    if ($value) {
      return $this->attributes['tgl_transaksi'] = \Carbon\Carbon::createFromFormat("d/m/Y",$value)->toDateTimeString();
    }else {
      return Carbon::now();
    }
  }

  public function setDibayarAttribute($value='')
  {
    return $this->attributes['dibayar'] = str_replace(['Rp ',',00','.'],'',$value);
  }

  public function getTotalHargaAttribute()
  {
    $total_harga = 0;
    $penjualan = $this->penjualan;
    if (count($penjualan)) {
      foreach ($penjualan as $p) {
        $total_harga += $p->harga_jual * $p->jumlah;
      }
    }
    return $total_harga-($total_harga*$this->diskon/100);
  }

  public function getPureTotalHargaAttribute()
  {
    $total_harga = 0;
    $penjualan = $this->penjualan;
    if (count($penjualan)) {
      foreach ($penjualan as $p) {
        $total_harga += $p->harga_jual * $p->jumlah;
      }
    }
    return $total_harga;
  }

  public function getTotalModalAttribute()
  {
    $total_modal = 0;
    $penjualan = $this->penjualan;
    if (count($penjualan)) {
      foreach ($penjualan as $p) {
        $total_modal += $p->harga_modal * $p->jumlah;
      }
    }
    return $total_modal;
  }

  public function getLabaAttribute()
  {
    return $this->total_harga - $this->total_modal;
  }
}
