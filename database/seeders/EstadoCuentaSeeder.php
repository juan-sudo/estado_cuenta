<?php

namespace Database\Seeders;

use App\Models\EstadoCuenta;
use Illuminate\Database\Seeder;

class EstadoCuentaSeeder extends Seeder
{
    public function run(): void
    {
        $registros = [
            // Juan Carlos Pérez Rodríguez (C-0001)
            ['codigo_contribuyente' => 'C-0001', 'anio' => 2023, 'tipo_estado' => 'PAGADO', 'monto' => 350.00, 'importe' => 350.00, 'codigo_compuesto' => 'C-0001-2023-PREDIAL'],
            ['codigo_contribuyente' => 'C-0001', 'anio' => 2024, 'tipo_estado' => 'DEUDA', 'monto' => 380.00, 'importe' => 380.00, 'codigo_compuesto' => 'C-0001-2024-PREDIAL'],
            ['codigo_contribuyente' => 'C-0001', 'anio' => 2025, 'tipo_estado' => 'POR PAGAR', 'monto' => 410.00, 'importe' => 410.00, 'codigo_compuesto' => 'C-0001-2025-PREDIAL'],

            // María Fernanda Torres Quispe (C-0002)
            ['codigo_contribuyente' => 'C-0002', 'anio' => 2023, 'tipo_estado' => 'PAGADO', 'monto' => 220.00, 'importe' => 220.00, 'codigo_compuesto' => 'C-0002-2023-ARBITRIOS'],
            ['codigo_contribuyente' => 'C-0002', 'anio' => 2024, 'tipo_estado' => 'PAGADO', 'monto' => 230.00, 'importe' => 230.00, 'codigo_compuesto' => 'C-0002-2024-ARBITRIOS'],
            ['codigo_contribuyente' => 'C-0002', 'anio' => 2025, 'tipo_estado' => 'DEUDA', 'monto' => 245.00, 'importe' => 245.00, 'codigo_compuesto' => 'C-0002-2025-ARBITRIOS'],

            // Luis Alberto Gómez Salazar (C-0003)
            ['codigo_contribuyente' => 'C-0003', 'anio' => 2022, 'tipo_estado' => 'DEUDA', 'monto' => 500.00, 'importe' => 500.00, 'codigo_compuesto' => 'C-0003-2022-PREDIAL'],
            ['codigo_contribuyente' => 'C-0003', 'anio' => 2023, 'tipo_estado' => 'DEUDA', 'monto' => 520.00, 'importe' => 520.00, 'codigo_compuesto' => 'C-0003-2023-PREDIAL'],
            ['codigo_contribuyente' => 'C-0003', 'anio' => 2024, 'tipo_estado' => 'POR PAGAR', 'monto' => 540.00, 'importe' => 540.00, 'codigo_compuesto' => 'C-0003-2024-PREDIAL'],
            ['codigo_contribuyente' => 'C-0003', 'anio' => 2025, 'tipo_estado' => 'POR PAGAR', 'monto' => 560.00, 'importe' => 560.00, 'codigo_compuesto' => 'C-0003-2025-PREDIAL'],
        ];

        foreach ($registros as $registro) {
            EstadoCuenta::updateOrCreate(
                [
                    'codigo_contribuyente' => $registro['codigo_contribuyente'],
                    'anio' => $registro['anio'],
                    'codigo_compuesto' => $registro['codigo_compuesto'],
                ],
                $registro
            );
        }
    }
}
