<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'cotizaciones';

    protected $fillable = [
        'cliente_id',
        'usuario_id',
        'folio',
        'fecha',
        'vigencia',
        'estado',
        'subtotal',
        'impuesto',
        'total',
        'notas',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'vigencia' => 'date',
            'subtotal' => 'decimal:2',
            'impuesto' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(DetalleCotizacion::class);
    }

    public function venta(): HasOne
    {
        return $this->hasOne(Venta::class);
    }
}
