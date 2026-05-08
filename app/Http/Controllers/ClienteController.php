<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClienteController extends Controller
{
    public function index(Request $request): View
    {
        $clientes = Cliente::query()
            ->when($request->filled('buscar'), fn ($query) => $query->where(function ($query) use ($request): void {
                $query->where('nombre', 'like', "%{$request->buscar}%")
                    ->orWhere('empresa', 'like', "%{$request->buscar}%")
                    ->orWhere('rfc', 'like', "%{$request->buscar}%");
            }))
            ->when($request->filled('estado'), fn ($query) => $query->where('activo', $request->estado === 'activo'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('modules.placeholder', [
            'titulo' => 'Clientes',
            'descripcion' => 'Registro, consulta y mantenimiento de clientes.',
            'items' => $clientes,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Cliente::create($this->validated($request));

        return back()->with('status', 'Cliente registrado correctamente.');
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $cliente->update($this->validated($request, $cliente));

        return back()->with('status', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        $cliente->delete();

        return back()->with('status', 'Cliente eliminado correctamente.');
    }

    private function validated(Request $request, ?Cliente $cliente = null): array
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'empresa' => ['nullable', 'string', 'max:255'],
            'rfc' => ['nullable', 'string', 'max:13', Rule::unique('clientes', 'rfc')->ignore($cliente)],
            'email' => ['nullable', 'email', 'max:255'],
            'telefono' => ['nullable', 'string', 'max:30'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'ciudad' => ['nullable', 'string', 'max:255'],
            'estado' => ['nullable', 'string', 'max:255'],
            'codigo_postal' => ['nullable', 'string', 'max:12'],
            'activo' => ['nullable', 'boolean'],
        ]);

        return [...$data, 'activo' => $request->boolean('activo', true)];
    }
}
