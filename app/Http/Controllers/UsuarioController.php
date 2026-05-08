<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function index(Request $request): View
    {
        $usuarios = User::with('role')
            ->when($request->filled('buscar'), fn ($query) => $query->where(function ($query) use ($request): void {
                $query->where('name', 'like', "%{$request->buscar}%")
                    ->orWhere('email', 'like', "%{$request->buscar}%");
            }))
            ->when($request->filled('role_id'), fn ($query) => $query->where('role_id', $request->role_id))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('modules.placeholder', [
            'titulo' => 'Usuarios',
            'descripcion' => 'Administracion de usuarios y asignacion de roles.',
            'items' => $usuarios,
            'roles' => Role::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:usuarios,email'],
            'password' => ['required', 'string', 'min:8'],
            'activo' => ['nullable', 'boolean'],
        ]);

        User::create([...$data, 'password' => Hash::make($data['password']), 'activo' => $request->boolean('activo', true)]);

        return back()->with('status', 'Usuario creado correctamente.');
    }

    public function update(Request $request, User $usuario): RedirectResponse
    {
        $data = $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('usuarios', 'email')->ignore($usuario)],
            'password' => ['nullable', 'string', 'min:8'],
            'activo' => ['nullable', 'boolean'],
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $usuario->update([...$data, 'activo' => $request->boolean('activo')]);

        return back()->with('status', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario): RedirectResponse
    {
        abort_if(auth()->id() === $usuario->id, 422, 'No puedes eliminar tu propio usuario.');

        $usuario->delete();

        return back()->with('status', 'Usuario eliminado correctamente.');
    }
}
