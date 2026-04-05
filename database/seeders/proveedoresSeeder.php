<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProveedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('proveedores')->insert([
         [
    'nombre' => 'Bavaria S.A.',
    'contacto' => '3174567890',
    'direccion' => 'Carrera 15 # 10-24, Málaga - Santander',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'nombre' => 'Coca-Cola FEMSA',
    'contacto' => '3126547890',
    'direccion' => 'Calle 12 # 9-30, Málaga - Santander',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'nombre' => 'Postobón S.A.',
    'contacto' => '3149876540',
    'direccion' => 'Carrera 10 # 14-18, Málaga - Santander',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'nombre' => 'FritoLay Colombia',
    'contacto' => '3151239876',
    'direccion' => 'Calle 8 # 7-22, Málaga - Santander',
    'created_at' => now(),
    'updated_at' => now(),
],
[
    'nombre' => 'Tiempos Mesas',
    'contacto' => '9999999999',
    'direccion' => 'Málaga - Santander',
    'created_at' => now(),
    'updated_at' => now(),
],

        ]);
    }
}
