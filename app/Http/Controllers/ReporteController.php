<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReporteController extends Controller
{
    public function index(Request $request): View
    {
        $inicio = $request->filled('inicio') ? $request->date('inicio') : now()->startOfMonth();
        $fin = $request->filled('fin') ? $request->date('fin') : now();

        return view('modules.placeholder', [
            'titulo' => 'Reportes',
            'descripcion' => 'Indicadores de ventas y cotizaciones por periodo.',
            'ventasTotal' => Venta::whereBetween('fecha', [$inicio, $fin])->sum('total'),
            'cotizacionesTotal' => Cotizacion::whereBetween('fecha', [$inicio, $fin])->sum('total'),
            'items' => Venta::with('cliente')->whereBetween('fecha', [$inicio, $fin])->latest()->paginate(10),
        ]);
    }
}
