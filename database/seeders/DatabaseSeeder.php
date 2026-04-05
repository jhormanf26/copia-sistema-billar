<?php

namespace Database\Seeders;

use App\Models\mesas;
use App\Models\mesasConsumos;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            proveedoresSeeder::class,
            ProductosSeeder::class,
            MesasSeeder::class,
            ComprasSeeder::class,
        ]);

    }
}
