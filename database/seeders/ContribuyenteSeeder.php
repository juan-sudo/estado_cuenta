<?php

namespace Database\Seeders;

use App\Models\Contribuyente;
use Illuminate\Database\Seeder;

class ContribuyenteSeeder extends Seeder
{
    public function run(): void
    {
        $contribuyentes = [
            [
                'codigo' => 'C-0001',
                'nombre' => 'Juan Carlos Pérez Rodríguez',
                'dni' => '45678912',
                'direccion' => 'Av. Los Álamos 123, Lima',
                'telefono' => '987654321',
                'correo' => 'juan.perez@example.com',
            ],
            [
                'codigo' => 'C-0002',
                'nombre' => 'María Fernanda Torres Quispe',
                'dni' => '41234567',
                'direccion' => 'Jr. Las Begonias 456, Lima',
                'telefono' => '976543210',
                'correo' => 'maria.torres@example.com',
            ],
            [
                'codigo' => 'C-0003',
                'nombre' => 'Luis Alberto Gómez Salazar',
                'dni' => '48912345',
                'direccion' => 'Calle Las Camelias 789, San Isidro',
                'telefono' => '965432109',
                'correo' => 'luis.gomez@example.com',
            ],
        ];

        foreach ($contribuyentes as $contribuyente) {
            Contribuyente::updateOrCreate(
                ['codigo' => $contribuyente['codigo']],
                $contribuyente
            );
        }
    }
}
