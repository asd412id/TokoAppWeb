<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
  protected $table = 'kategori';

  public function barang()
  {
    return $this->belongsToMany(Barang::class,'barang_kategori');
  }
}
