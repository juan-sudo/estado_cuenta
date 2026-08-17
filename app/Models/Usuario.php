<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Usuario extends Model
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'codigo_contribuyente',
        'dni',
        'email',
        'password',
        'codigo_verificacion',
        'codigo_expira_en',
        'email_verified_at',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'codigo_expira_en' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function contribuyente(): BelongsTo
    {
        return $this->belongsTo(Contribuyente::class, 'codigo_contribuyente', 'codigo');
    }
}
