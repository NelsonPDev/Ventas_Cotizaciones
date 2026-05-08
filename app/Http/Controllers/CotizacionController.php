<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\DetalleCotizacion;
use App\Models\Venta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CotizacionController extends Controller
{
    public function index(Request $request): View
    {
        $cotizaciones = Cotizacion::with('cliente')
            ->when($request->filled('estado'), fn ($query) => $query->where('estado', $request->estado))
            ->when($request->filled('buscar'), fn ($query) => $query->where('folio', 'like', "%{$request->buscar}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('modules.placeholder', [
            'titulo' => 'Cotizaciones',
            'descripcion' => 'Creacion, seguimiento y conversion de cotizaciones.',
            'items' => $cotizaciones,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'fecha' => ['required', 'date'],
            'vigencia' => ['nullable', 'date', 'after_or_equal:fecha'],
            'notas' => ['nullable', 'string'],
            'productos' => ['required', 'array', 'min:1'],
            'productos.*.producto_id' => ['required', 'exists:productos,id'],
            'productos.*.cantidad' => ['required', 'integer', 'min:1'],
            'productos.*.precio_unitario' => ['required', 'numeric', 'min:0'],
            'productos.*.descuento' => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($data): void {
            $cotizacion = Cotizacion::create([
                'cliente_id' => $data['cliente_id'],
                'usuario_id' => auth()->id(),
                'folio' => 'COT-'.now()->format('YmdHis'),
                'fecha' => $data['fecha'],
                'vigencia' => $data['vigencia'] ?? null,
                'notas' => $data['notas'] ?? null,
            ]);

            $this->syncDetalles($cotizacion, $data['productos']);
        });

        return back()->with('status', 'Cotizacion creada correctamente.');
    }

    public function changeStatus(Request $request, Cotizacion $cotizacion): RedirectResponse
    {
        $data = $request->validate([
            'estado' => ['required', 'in:pendiente,aceptada,rechazada'],
        ]);

        $cotizacion->update($data);

        return back()->with('status', 'Estado de cotizacion actualizado.');
    }

    public function update(Request $request, Cotizacion $cotizacion): RedirectResponse
    {
        $data = $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'fecha' => ['required', 'date'],
            'vigencia' => ['nullable', 'date', 'after_or_equal:fecha'],
            'estado' => ['required', 'in:pendiente,aceptada,rechazada'],
            'notas' => ['nullable', 'string'],
        ]);

        $cotizacion->update($data);

        return back()->with('status', 'Cotizacion actualizada correctamente.');
    }

    public function destroy(Cotizacion $cotizacion): RedirectResponse
    {
        $cotizacion->delete();

        return back()->with('status', 'Cotizacion eliminada correctamente.');
    }

    public function convertToVenta(Cotizacion $cotizacion): RedirectResponse
    {
        abort_if($cotizacion->venta()->exists(), 422, 'La cotizacion ya fue convertida en venta.');

        DB::transaction(function () use ($cotizacion): void {
            $cotizacion->load('detalles');

            $venta = Venta::create([
                'cliente_id' => $cotizacion->cliente_id,
                'cotizacion_id' => $cotizacion->id,
                'usuario_id' => auth()->id(),
                'folio' => 'VEN-'.now()->format('YmdHis'),
                'fecha' => now()->toDateString(),
                'subtotal' => $cotizacion->subtotal,
                'impuesto' => $cotizacion->impuesto,
                'total' => $cotizacion->total,
                'notas' => 'Venta generada desde cotizacion '.$cotizacion->folio,
            ]);

            foreach ($cotizacion->detalles as $detalle) {
                $venta->detalles()->create($detalle->only([
                    'producto_id',
                    'cantidad',
                    'precio_unitario',
                    'descuento',
                    'subtotal',
                ]));
            }

            $cotizacion->update(['estado' => 'aceptada']);
        });

        return redirect()->route('ventas.index')->with('status', 'Cotizacion convertida en venta.');
    }

    private function syncDetalles(Cotizacion $cotizacion, array $productos): void
    {
        $subtotal = 0;

        foreach ($productos as $producto) {
            $lineaSubtotal = max(0, ($producto['cantidad'] * $producto['precio_unitario']) - ($producto['descuento'] ?? 0));
            $subtotal += $lineaSubtotal;

            DetalleCotizacion::create([
                'cotizacion_id' => $cotizacion->id,
                'producto_id' => $producto['producto_id'],
                'cantidad' => $producto['cantidad'],
                'precio_unitario' => $producto['precio_unitario'],
                'descuento' => $producto['descuento'] ?? 0,
                'subtotal' => $lineaSubtotal,
            ]);
        }

        $impuesto = round($subtotal * 0.16, 2);
        $cotizacion->update([
            'subtotal' => $subtotal,
            'impuesto' => $impuesto,
            'total' => $subtotal + $impuesto,
        ]);
    }
}
