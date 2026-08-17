<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstadoCuenta extends Model
{
    use HasFactory;

    protected $table = 'estadocuenta';

    protected $fillable = [
        'codigo_contribuyente',
        'anio',
        'tipo_estado',
        'monto',
        'importe',
        'codigo_compuesto',
    ];

    protected $casts = [
        'anio' => 'integer',
        'monto' => 'decimal:2',
        'importe' => 'decimal:2',
    ];

    /**
     * Estados considerados como deuda pendiente de pago.
     */
    public const ESTADOS_PENDIENTES = ['DEUDA', 'POR PAGAR', 'PENDIENTE'];

    public function contribuyente(): BelongsTo
    {
        return $this->belongsTo(Contribuyente::class, 'codigo_contribuyente', 'codigo');
    }

    public function estaPendiente(): bool
    {
        return in_array(strtoupper((string) $this->tipo_estado), self::ESTADOS_PENDIENTES, true);
    }
}
