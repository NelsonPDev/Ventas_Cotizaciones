<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('empresa')->nullable();
            $table->string('rfc', 13)->nullable()->unique();
            $table->string('email')->nullable();
            $table->string('telefono', 30)->nullable();
            $table->string('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('estado')->nullable();
            $table->string('codigo_postal', 12)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->nullOnDelete();
            $table->string('sku')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 12, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('folio')->unique();
            $table->date('fecha');
            $table->date('vigencia')->nullable();
            $table->enum('estado', ['pendiente', 'aceptada', 'rechazada'])->default('pendiente');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('impuesto', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('detalle_cotizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cotizacion_id')->constrained('cotizaciones')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });

        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('cotizacion_id')->nullable()->constrained('cotizaciones')->nullOnDelete();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('folio')->unique();
            $table->date('fecha');
            $table->enum('estado', ['registrada', 'cancelada'])->default('registrada');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('impuesto', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->cascadeOnDelete();
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnUpdate()->restrictOnDelete();
            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario', 12, 2);
            $table->decimal('descuento', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
        Schema::dropIfExists('ventas');
        Schema::dropIfExists('detalle_cotizaciones');
        Schema::dropIfExists('cotizaciones');
        Schema::dropIfExists('productos');
        Schema::dropIfExists('categorias');
        Schema::dropIfExists('clientes');
    }
};
