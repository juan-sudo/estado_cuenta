<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contribuyente extends Model
{
    use HasFactory;

    /**
     * La tabla en BD usa nombre en singular, distinto a la convención de Laravel.
     */
    protected $table = 'contribuyente';

    /**
     * La PK es el código (string), no autoincremental.
     */
    protected $primaryKey = 'codigo';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'dni',
        'direccion',
        'telefono',
        'correo',
    ];

    /**
     * Relación: un contribuyente tiene muchos registros de estado de cuenta.
     */
    public function estadosCuenta(): HasMany
    {
        return $this->hasMany(EstadoCuenta::class, 'codigo_contribuyente', 'codigo');
    }

    /**
     * Scope de búsqueda flexible por dni, código o nombre completo.
     */
    public function scopeBuscar($query, string $termino)
    {
        $termino = trim($termino);

        return $query->where(function ($q) use ($termino) {
            $q->where('dni', $termino)
                ->orWhere('codigo', $termino)
                ->orWhere('nombre', 'like', "%{$termino}%");
        });
    }
}
