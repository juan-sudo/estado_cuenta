@extends('layouts.app')

@section('titulo', 'Consulta de Estado de Cuenta')

@section('contenido')
<div class="space-y-6">
    <section class="consulta-hero">
        <div class="consulta-hero__heading">
            <span class="consulta-hero__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 19.5V7.8a1.8 1.8 0 0 1 1.8-1.8h12.4A1.8 1.8 0 0 1 20 7.8v11.7"/><path d="M2.5 19.5h19M8 10h2M8 14h8M16 10h.01"/></svg></span>
            <div><p class="eyebrow text-primary-600">Atención al contribuyente</p><h1>Consulta tu estado de cuenta</h1><p>Revisa tus obligaciones, pagos y saldos registrados.</p></div>
        </div>
        <form method="GET" action="{{ route('consulta.index') }}" class="consulta-search">
            <div class="input-with-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="m20 20-4.2-4.2"/></svg><input type="text" name="q" value="{{ $termino }}" class="input-field" placeholder="DNI, código o nombre completo" autofocus autocomplete="off"></div>
            <button type="submit" class="btn-primary consulta-search__button"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="6"/><path d="m20 20-4.2-4.2"/></svg> Buscar</button>
        </form>
        <p class="consulta-hero__help">Ingresa al menos uno de los datos del contribuyente para realizar la búsqueda.</p>
    </section>

    @if($buscoSinResultados)
        <div class="consulta-alert" role="status"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 8v4M12 16h.01"/></svg><span>No se encontró ningún contribuyente que coincida con <strong>“{{ $termino }}”</strong>.</span></div>
    @endif

    @if($contribuyente)
        <div class="print-actions no-print">
            <p>Estado de cuenta actualizado</p>
            <button type="button" class="btn-secondary print-button" onclick="window.print()"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M6 9V3h12v6M6 17H4a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v4a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v7H6z"/></svg> Imprimir estado de cuenta</button>
        </div>

        <div class="print-document">
        <section class="contribuyente-card">
            <div class="contribuyente-card__identity"><span class="contribuyente-card__avatar" aria-hidden="true">{{ strtoupper(mb_substr($contribuyente->nombre, 0, 1)) }}</span><div><p class="eyebrow text-primary-600">Contribuyente</p><h2>{{ $contribuyente->nombre }}</h2><p>Código {{ $contribuyente->codigo }} <span aria-hidden="true">·</span> DNI {{ $contribuyente->dni ?? '—' }}</p></div></div>
            <div class="contribuyente-card__contact"><p>{{ $contribuyente->direccion ?? 'Sin dirección registrada' }}</p><p>{{ $contribuyente->telefono ?? '—' }} @if($contribuyente->correo) <span aria-hidden="true">·</span> {{ $contribuyente->correo }} @endif</p></div>
        </section>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="summary-card summary-card--debt"><span class="summary-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 8v4M12 16h.01"/><circle cx="12" cy="12" r="9"/></svg></span><div><p>Total pendiente</p><strong>S/ {{ number_format($resumen['total_deuda'], 2) }}</strong></div></div>
            <div class="summary-card summary-card--paid"><span class="summary-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m7 12 3 3 7-7"/><circle cx="12" cy="12" r="9"/></svg></span><div><p>Total pagado</p><strong>S/ {{ number_format($resumen['total_pagado'], 2) }}</strong></div></div>
            <div class="summary-card summary-card--records"><span class="summary-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7 3h8l3 3v15H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z"/><path d="M9 12h6M9 16h6M15 3v4h4"/></svg></span><div><p>Registros encontrados</p><strong>{{ $resumen['cantidad_registros'] }}</strong></div></div>
        </div>

        <section class="records-card">
            <div class="records-card__heading"><div><h2>Detalle del estado de cuenta</h2><p>Movimientos y obligaciones registradas para este contribuyente.</p></div><span>{{ $resumen['cantidad_registros'] }} {{ $resumen['cantidad_registros'] === 1 ? 'registro' : 'registros' }}</span></div>
            <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200 text-sm"><thead><tr><th>Año</th><th>Estado</th><th>Código compuesto</th><th>Monto</th><th>Importe</th></tr></thead><tbody class="divide-y divide-slate-100">
                @forelse($estados as $estado)
                    <tr><td>{{ $estado->anio }}</td><td>@php $esPendiente = $estado->estaPendiente(); $esPagado = strtoupper((string) $estado->tipo_estado) === 'PAGADO'; @endphp<span class="badge {{ $esPendiente ? 'bg-red-100 text-red-700' : ($esPagado ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600') }}">{{ $estado->tipo_estado ?? 'Sin definir' }}</span></td><td>{{ $estado->codigo_compuesto ?? '—' }}</td><td class="text-right">S/ {{ number_format((float) $estado->monto, 2) }}</td><td class="text-right font-semibold text-slate-900">S/ {{ number_format((float) $estado->importe, 2) }}</td></tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">Este contribuyente no tiene registros de estado de cuenta.</td></tr>
                @endforelse
            </tbody></table></div>
        </section>
        </div>
    @endif
</div>
@endsection
