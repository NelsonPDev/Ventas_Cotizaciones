<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Producto;
use App\Models\Venta;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('dashboard', [
            'clientesActivos' => Cliente::where('activo', true)->count(),
            'cotizacionesPendientes' => Cotizacion::where('estado', 'pendiente')->count(),
            'ventasMes' => Venta::whereMonth('fecha', now()->month)
                ->whereYear('fecha', now()->year)
                ->sum('total'),
            'productosActivos' => Producto::where('activo', true)->count(),
            'cotizacionesRecientes' => Cotizacion::with('cliente')->latest()->take(5)->get(),
            'ventasRecientes' => Venta::with('cliente')->latest()->take(5)->get(),
        ]);
    }
}
