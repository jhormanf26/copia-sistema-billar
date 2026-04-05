<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\User;  // Añade esta línea para importar el modelo User
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'numerodocumento' => '123456789',
            'tipo' => 'admin',
            'estado' => 'activo',
            'tipodocumento' => 'cc'
        ]);

        User::create([
            'name' => 'desarrollo',
            'email' => 'a@a.a',
            'password' => Hash::make('12345678'),
            'numerodocumento' => '12345678',
            'tipo' => 'admin',
            'estado' => 'activo',
            'tipodocumento' => 'cc'
        ]);


    }
}
