<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
  protected $table = 'penjualan';
  protected $fillable = [
    'uuid',
    'transaksi_id',
    'barang_id',
    'kode',
    'nama',
    'harga_modal',
    'harga_jual',
    'jumlah',
  ];
  protected $dates = [
    'created_at',
    'updated_at',
  ];

  public function barangById()
  {
    return $this->belongsTo(Barang::class,'barang_id');
  }

  public function barangByKode()
  {
    return $this->belongsTo(Barang::class,'kode','kode');
  }

  public function transaksi()
  {
    return $this->belongsTo(Transaksi::class);
  }
}
