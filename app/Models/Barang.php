<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
  protected $table = 'barang';
  protected $fillable = ['jumlah'];

  public function kategori()
  {
    return $this->belongsToMany(Kategori::class,'barang_kategori');
  }

  public function setHargaModalAttribute($value='')
  {
    return $this->attributes['harga_modal'] = str_replace(['Rp ',',00','.'],'',$value);
  }

  public function setHargaJualAttribute($value='')
  {
    return $this->attributes['harga_jual'] = str_replace(['Rp ',',00','.'],'',$value);
  }

  public function penjualanById()
  {
    return $this->hasMany(Penjualan::class);
  }

  public function penjualanByKode()
  {
    return $this->hasMany(Penjualan::class,'kode','kode');
  }
}
