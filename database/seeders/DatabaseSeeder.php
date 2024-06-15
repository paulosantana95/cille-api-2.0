<?php

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // User::factory(10)->create();

    User::query()->create([
      'name' => 'admin',
      'email' => 'rochaeternodutra@gmail.com',
      'password' => 'teste123456',
      'role_id' => 1,
      'cellphone' => '85998576480',
      'company' => 'teste of company',
      'shopee_id' => '112840',
      'cnpj' => '929629919',
      'birth_date' => '2020-02-01',
    ]);
  }
}
