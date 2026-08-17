<?php

namespace App\Http\Controllers;

use App\Models\Contribuyente;
use App\Models\EstadoCuenta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultaController extends Controller
{
    /**
     * Pantalla principal de consulta: buscador + resultados (si hay búsqueda).
     */
    public function index(Request $request): View
    {
        $termino = trim((string) $request->query('q', ''));
        $contribuyente = null;
        $estados = collect();
        $resumen = null;

        if ($termino !== '') {
            $contribuyente = Contribuyente::query()
                ->buscar($termino)
                ->first();

            if ($contribuyente) {
                $estados = EstadoCuenta::query()
                    ->where('codigo_contribuyente', $contribuyente->codigo)
                    ->orderByDesc('anio')
                    ->get();

                $resumen = $this->calcularResumen($estados);
            }
        }

        return view('consulta.index', [
            'termino' => $termino,
            'contribuyente' => $contribuyente,
            'estados' => $estados,
            'resumen' => $resumen,
            'buscoSinResultados' => $termino !== '' && ! $contribuyente,
        ]);
    }

    /**
     * Calcula totales de deuda / por pagar / pagado a partir de la colección de estados de cuenta.
     */
    private function calcularResumen($estados): array
    {
        $totalDeuda = 0.0;
        $totalPagado = 0.0;
        $totalGeneral = 0.0;

        foreach ($estados as $estado) {
            $importe = (float) $estado->importe;
            $totalGeneral += $importe;

            if ($estado->estaPendiente()) {
                $totalDeuda += $importe;
            } elseif (strtoupper((string) $estado->tipo_estado) === 'PAGADO') {
                $totalPagado += $importe;
            }
        }

        return [
            'total_deuda' => $totalDeuda,
            'total_pagado' => $totalPagado,
            'total_general' => $totalGeneral,
            'cantidad_registros' => $estados->count(),
        ];
    }
}
