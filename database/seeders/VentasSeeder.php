<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VentasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = DB::table('productos')->get();
        if ($productos->isEmpty()) {
            return;
        }

        $mesas = DB::table('mesas')->get();
        if ($mesas->isEmpty()) {
            return;
        }

        $metodos_pago = ['efectivo', 'transferencia', 'tarjeta'];

        // Sembrar 10 ventas para el día de hoy
        for ($i = 1; $i <= 10; $i++) {
            $inicio = Carbon::now()->subHours(rand(1, 10))->subMinutes(rand(1, 59));
            $fin = (clone $inicio)->addMinutes(rand(30, 180));
            $mesa = $mesas->random();
            $metodo = $metodos_pago[array_rand($metodos_pago)];

            $ventaId = DB::table('mesasventas')->insertGetId([
                'fechainicio' => $inicio,
                'fechafin' => $fin,
                'total' => 0, // Lo calculamos luego
                'idmesa' => $mesa->idmesa,
                'metodo_pago' => $metodo,
                'created_at' => $fin,
                'updated_at' => $fin,
            ]);

            $numProductos = rand(1, 4);
            $totalVenta = 0;

            for ($j = 0; $j < $numProductos; $j++) {
                $producto = $productos->random();
                $cantidad = rand(1, 3);
                $precio = $producto->precio;
                $subtotal = $cantidad * $precio;
                $totalVenta += $subtotal;

                DB::table('mesasventas_productos')->insert([
                    'idmesaventa' => $ventaId,
                    'idproducto' => $producto->idproducto,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precio,
                    'subtotal' => $subtotal,
                    'created_at' => $fin,
                    'updated_at' => $fin,
                ]);
            }

            // Actualizar total de la venta
            DB::table('mesasventas')->where('id', $ventaId)->update([
                'total' => $totalVenta
            ]);
        }
    }
}
