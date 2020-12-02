<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class User extends Seeder
{
  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    \DB::table('users')->insert([
      'uuid' => (string) Str::uuid(),
      'name' => 'Administrator',
      'username' => 'admin',
      'password' => bcrypt('adminpass'),
      'role' => 'admin'
    ]);
  }
}
