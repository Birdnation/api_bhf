<?php

namespace Database\Seeders;

use App\Models\Periodo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@ucn.cl',
            'rut' => '11111111-1',
            'depto' => 'administrador',
            'rol' => 'administrador',
            'password' => bcrypt('123456789'),
            'remember_token' => Str::random(10),
        ]);

        Periodo::create([
            'anio' => 2021,
            'user_id' => 1
        ]);
    }
}
