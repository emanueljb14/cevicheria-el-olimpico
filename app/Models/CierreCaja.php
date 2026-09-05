<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CierreCaja extends Model
{
    use HasFactory;

    protected $table = 'cierre_cajas';

    protected $fillable = [
        'user_id',
        'monto_apertura',
        'efectivo_fisico',
        'digital_fisico',
        'total_sistema',
        'diferencia',
        'estado',
        'observacion',
    ];

    /**
     * Relación: El cierre pertenece a un usuario (cajero).
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}