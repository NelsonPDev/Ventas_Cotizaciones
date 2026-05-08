<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VentaController extends Controller
{
    public function index(Request $request): View
    {
        $ventas = Venta::with('cliente')
            ->when($request->filled('buscar'), fn ($query) => $query->where('folio', 'like', "%{$request->buscar}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('modules.placeholder', [
            'titulo' => 'Ventas',
            'descripcion' => 'Registro y consulta de ventas asociadas a clientes.',
            'items' => $ventas,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'fecha' => ['required', 'date'],
            'subtotal' => ['required', 'numeric', 'min:0'],
            'impuesto' => ['nullable', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
            'notas' => ['nullable', 'string'],
        ]);

        Venta::create([
            ...$data,
            'usuario_id' => auth()->id(),
            'folio' => 'VEN-'.now()->format('YmdHis'),
            'impuesto' => $data['impuesto'] ?? 0,
        ]);

        return back()->with('status', 'Venta registrada correctamente.');
    }
}
