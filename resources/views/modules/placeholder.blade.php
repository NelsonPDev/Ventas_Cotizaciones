@extends('layouts.app')

@section('title', $titulo)
@section('page-title', $titulo)

@section('content')
    <div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
        <div>
            <p class="text-sm font-medium text-slate-500">{{ $descripcion }}</p>
            <h2 class="mt-1 text-2xl font-semibold text-slate-950">{{ $titulo }}</h2>
        </div>

        <form method="GET" class="flex w-full flex-col gap-3 sm:flex-row lg:w-auto">
            <input name="buscar" value="{{ request('buscar') }}" class="form-input sm:w-72" placeholder="Buscar">
            <button class="btn-secondary" type="submit">Filtrar</button>
        </form>
    </div>

    @isset($ventasTotal)
        <div class="mb-6 grid gap-4 sm:grid-cols-2">
            <x-stat-card label="Ventas del periodo" :value="'$'.number_format((float) $ventasTotal, 2)" tone="emerald" />
            <x-stat-card label="Cotizaciones del periodo" :value="'$'.number_format((float) $cotizacionesTotal, 2)" tone="sky" />
        </div>
    @endisset

    <section class="rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase text-slate-500">
                    <tr>
                        <th class="px-5 py-3">Registro</th>
                        <th class="px-5 py-3">Referencia</th>
                        <th class="px-5 py-3">Estado</th>
                        <th class="px-5 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($items as $item)
                        <tr>
                            <td class="px-5 py-3 font-medium">
                                {{ $item->nombre ?? $item->name ?? $item->folio ?? 'Registro #'.$item->id }}
                            </td>
                            <td class="px-5 py-3 text-slate-600">
                                {{ $item->cliente?->nombre ?? $item->empresa ?? $item->email ?? $item->role?->nombre ?? '-' }}
                            </td>
                            <td class="px-5 py-3">
                                @if(isset($item->estado))
                                    <span class="badge">{{ ucfirst($item->estado) }}</span>
                                @elseif(isset($item->activo))
                                    <span class="badge">{{ $item->activo ? 'Activo' : 'Inactivo' }}</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right">
                                @isset($item->total)
                                    ${{ number_format((float) $item->total, 2) }}
                                @else
                                    -
                                @endisset
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-8 text-center text-slate-500">Sin registros disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($items, 'links'))
            <div class="border-t border-slate-200 px-5 py-4">
                {{ $items->links() }}
            </div>
        @endif
    </section>
@endsection
