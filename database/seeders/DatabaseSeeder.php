<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Cotizacion;
use App\Models\Producto;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles = collect([
            ['nombre' => 'Administrador', 'descripcion' => 'Acceso completo al sistema.'],
            ['nombre' => 'Usuario Comercial', 'descripcion' => 'Gestiona clientes, cotizaciones y ventas.'],
            ['nombre' => 'Usuario de Consulta', 'descripcion' => 'Consulta informacion y reportes.'],
        ])->mapWithKeys(fn (array $role) => [
            $role['nombre'] => Role::updateOrCreate(['nombre' => $role['nombre']], $role),
        ]);

        $admin = User::updateOrCreate(
            ['email' => 'admin@ventas.local'],
            [
                'role_id' => $roles['Administrador']->id,
                'name' => 'Administrador General',
                'password' => Hash::make('password'),
                'activo' => true,
            ],
        );

        $comercial = User::updateOrCreate(
            ['email' => 'comercial@ventas.local'],
            [
                'role_id' => $roles['Usuario Comercial']->id,
                'name' => 'Ejecutivo Comercial',
                'password' => Hash::make('password'),
                'activo' => true,
            ],
        );

        User::updateOrCreate(
            ['email' => 'consulta@ventas.local'],
            [
                'role_id' => $roles['Usuario de Consulta']->id,
                'name' => 'Usuario Consulta',
                'password' => Hash::make('password'),
                'activo' => true,
            ],
        );

        $servicios = Categoria::updateOrCreate(
            ['nombre' => 'Servicios'],
            ['descripcion' => 'Servicios profesionales y consultoria.', 'activo' => true],
        );

        $software = Categoria::updateOrCreate(
            ['nombre' => 'Software'],
            ['descripcion' => 'Licencias y soluciones digitales.', 'activo' => true],
        );

        $productos = collect([
            ['categoria_id' => $servicios->id, 'sku' => 'SRV-001', 'nombre' => 'Implementacion inicial', 'precio' => 12500, 'stock' => 20],
            ['categoria_id' => $servicios->id, 'sku' => 'SRV-002', 'nombre' => 'Soporte mensual', 'precio' => 3500, 'stock' => 50],
            ['categoria_id' => $software->id, 'sku' => 'SW-001', 'nombre' => 'Licencia CRM anual', 'precio' => 9800, 'stock' => 30],
            ['categoria_id' => $software->id, 'sku' => 'SW-002', 'nombre' => 'Modulo de reportes', 'precio' => 6400, 'stock' => 15],
        ])->map(fn (array $producto) => Producto::updateOrCreate(['sku' => $producto['sku']], [
            ...$producto,
            'descripcion' => 'Producto de prueba para el sistema.',
            'activo' => true,
        ]));

        $clientes = collect([
            ['nombre' => 'Ana Martinez', 'empresa' => 'Grupo Norte', 'rfc' => 'GNO240101AA1', 'email' => 'ana@gruponorte.local', 'telefono' => '555-0101'],
            ['nombre' => 'Luis Hernandez', 'empresa' => 'Comercial Delta', 'rfc' => 'CDE240101BB2', 'email' => 'luis@delta.local', 'telefono' => '555-0102'],
            ['nombre' => 'Sofia Perez', 'empresa' => 'Industrias Centro', 'rfc' => 'ICE240101CC3', 'email' => 'sofia@icentro.local', 'telefono' => '555-0103'],
        ])->map(fn (array $cliente) => Cliente::updateOrCreate(['rfc' => $cliente['rfc']], [
            ...$cliente,
            'direccion' => 'Av. Principal 123',
            'ciudad' => 'Ciudad de Mexico',
            'estado' => 'CDMX',
            'codigo_postal' => '01000',
            'activo' => true,
        ]));

        $cotizacion = Cotizacion::updateOrCreate(
            ['folio' => 'COT-20260420-001'],
            [
                'cliente_id' => $clientes[0]->id,
                'usuario_id' => $comercial->id,
                'fecha' => now()->toDateString(),
                'vigencia' => now()->addDays(15)->toDateString(),
                'estado' => 'pendiente',
                'notas' => 'Cotizacion de prueba con calculo automatico.',
            ],
        );

        $cotizacion->detalles()->delete();
        $subtotal = 0;

        foreach ($productos->take(2) as $index => $producto) {
            $cantidad = $index + 1;
            $lineaSubtotal = $cantidad * (float) $producto->precio;
            $subtotal += $lineaSubtotal;

            $cotizacion->detalles()->create([
                'producto_id' => $producto->id,
                'cantidad' => $cantidad,
                'precio_unitario' => $producto->precio,
                'descuento' => 0,
                'subtotal' => $lineaSubtotal,
            ]);
        }

        $impuesto = round($subtotal * 0.16, 2);
        $cotizacion->update([
            'subtotal' => $subtotal,
            'impuesto' => $impuesto,
            'total' => $subtotal + $impuesto,
        ]);

        $venta = $cotizacion->venta()->updateOrCreate(
            ['folio' => 'VEN-20260420-001'],
            [
                'cliente_id' => $cotizacion->cliente_id,
                'usuario_id' => $admin->id,
                'fecha' => now()->subDays(2)->toDateString(),
                'estado' => 'registrada',
                'subtotal' => $cotizacion->subtotal,
                'impuesto' => $cotizacion->impuesto,
                'total' => $cotizacion->total,
                'notas' => 'Venta de prueba asociada a cotizacion.',
            ],
        );

        $venta->detalles()->delete();

        foreach ($cotizacion->detalles as $detalle) {
            $venta->detalles()->create($detalle->only([
                'producto_id',
                'cantidad',
                'precio_unitario',
                'descuento',
                'subtotal',
            ]));
        }
    }
}
