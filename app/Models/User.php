<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{

  use HasFactory, Notifiable;

  /**
  * The attributes that are mass assignable.
  *
  * @var array
  */
  protected $fillable = [
    'name',
    'username',
    'password',
    'role',
  ];

  /**
  * The attributes that should be hidden for arrays.
  *
  * @var array
  */
  protected $hidden = [
    'password',
    'remember_token',
  ];

  /**
  * The attributes that should be cast to native types.
  *
  * @var array
  */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function setPasswordAttribute($value='')
  {
    return $this->attributes['password'] = bcrypt($value);
  }

  public function GetisAdminAttribute()
  {
    return $this->role == 'admin';
  }

  public function GetisCashierAttribute()
  {
    return $this->role == 'cashier';
  }

  public function GetisStokerAttribute()
  {
    return $this->role == 'stoker';
  }
}
