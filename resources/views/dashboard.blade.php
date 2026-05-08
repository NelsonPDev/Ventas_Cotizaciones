@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <p class="text-sm font-medium text-slate-500">Resumen general</p>
        <h2 class="mt-1 text-2xl font-semibold text-slate-950">Actividad comercial</h2>
    </div>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <x-stat-card label="Clientes activos" :value="$clientesActivos" tone="emerald" />
        <x-stat-card label="Cotizaciones pendientes" :value="$cotizacionesPendientes" tone="sky" />
        <x-stat-card label="Ventas del mes" :value="'$'.number_format((float) $ventasMes, 2)" tone="amber" />
        <x-stat-card label="Productos activos" :value="$productosActivos" tone="rose" />
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="text-base font-semibold">Cotizaciones recientes</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Folio</th>
                            <th class="px-5 py-3">Cliente</th>
                            <th class="px-5 py-3">Estado</th>
                            <th class="px-5 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($cotizacionesRecientes as $cotizacion)
                            <tr>
                                <td class="px-5 py-3 font-medium">{{ $cotizacion->folio }}</td>
                                <td class="px-5 py-3 text-slate-600">{{ $cotizacion->cliente?->nombre }}</td>
                                <td class="px-5 py-3"><span class="badge">{{ ucfirst($cotizacion->estado) }}</span></td>
                                <td class="px-5 py-3 text-right">${{ number_format((float) $cotizacion->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-6 text-center text-slate-500">Sin cotizaciones registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="text-base font-semibold">Ventas recientes</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Folio</th>
                            <th class="px-5 py-3">Cliente</th>
                            <th class="px-5 py-3">Fecha</th>
                            <th class="px-5 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($ventasRecientes as $venta)
                            <tr>
                                <td class="px-5 py-3 font-medium">{{ $venta->folio }}</td>
                                <td class="px-5 py-3 text-slate-600">{{ $venta->cliente?->nombre }}</td>
                                <td class="px-5 py-3 text-slate-600">{{ $venta->fecha?->format('d/m/Y') }}</td>
                                <td class="px-5 py-3 text-right">${{ number_format((float) $venta->total, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-6 text-center text-slate-500">Sin ventas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
