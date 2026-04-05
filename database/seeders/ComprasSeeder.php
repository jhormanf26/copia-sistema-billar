<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ComprasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Compra 1 - Bavaria
        $compra1 = DB::table('compras')->insertGetId([
            'idproveedor' => 1,
            'fecha_compra' => Carbon::now()->subDays(10),
            'total' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Detalles de compra 1
        DB::table('compra_detalles')->insert([
            [
                'idcompra' => $compra1,
                'idproducto' => 1,
                'cantidad' => 24,
                'precio_compra' => 2500.00,
                'precio_venta' => 5000.00,
                'subtotal' => 60000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idcompra' => $compra1,
                'idproducto' => 2,
                'cantidad' => 12,
                'precio_compra' => 3000.00,
                'precio_venta' => 6000.00,
                'subtotal' => 36000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Actualizar total de compra 1
        DB::table('compras')->where('id', $compra1)->update(['total' => 96000.00]);

        // Compra 2 - Coca-Cola FEMSA
        $compra2 = DB::table('compras')->insertGetId([
            'idproveedor' => 2,
            'fecha_compra' => Carbon::now()->subDays(5),
            'total' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Detalles de compra 2
        DB::table('compra_detalles')->insert([
            [
                'idcompra' => $compra2,
                'idproducto' => 10,
                'cantidad' => 30,
                'precio_compra' => 1500.00,
                'precio_venta' => 3000.00,
                'subtotal' => 45000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idcompra' => $compra2,
                'idproducto' => 11,
                'cantidad' => 20,
                'precio_compra' => 1800.00,
                'precio_venta' => 3500.00,
                'subtotal' => 36000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Actualizar total de compra 2
        DB::table('compras')->where('id', $compra2)->update(['total' => 81000.00]);

        // Compra 3 - Postobón S.A.
        $compra3 = DB::table('compras')->insertGetId([
            'idproveedor' => 3,
            'fecha_compra' => Carbon::now()->subDays(2),
            'total' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Detalles de compra 3
        DB::table('compra_detalles')->insert([
            [
                'idcompra' => $compra3,
                'idproducto' => 20,
                'cantidad' => 15,
                'precio_compra' => 2000.00,
                'precio_venta' => 4000.00,
                'subtotal' => 30000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Actualizar total de compra 3
        DB::table('compras')->where('id', $compra3)->update(['total' => 30000.00]);

        // Compra 4 - FritoLay Colombia (Hoy)
        $compra4 = DB::table('compras')->insertGetId([
            'idproveedor' => 4,
            'fecha_compra' => Carbon::now(),
            'total' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Detalles de compra 4
        DB::table('compra_detalles')->insert([
            [
                'idcompra' => $compra4,
                'idproducto' => 30,
                'cantidad' => 50,
                'precio_compra' => 800.00,
                'precio_venta' => 1500.00,
                'subtotal' => 40000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idcompra' => $compra4,
                'idproducto' => 31,
                'cantidad' => 40,
                'precio_compra' => 900.00,
                'precio_venta' => 1800.00,
                'subtotal' => 36000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'idcompra' => $compra4,
                'idproducto' => 32,
                'cantidad' => 25,
                'precio_compra' => 1200.00,
                'precio_venta' => 2500.00,
                'subtotal' => 30000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Actualizar total de compra 4
        DB::table('compras')->where('id', $compra4)->update(['total' => 106000.00]);
    }
}
