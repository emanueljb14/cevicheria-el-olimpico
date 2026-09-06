<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
    use HasFactory;

    protected $table = 'mesas';

    protected $fillable = [
        'numero',
        'capacidad',
        'ubicacion',
        'estado',
    ];

    /**
     * Casteo de atributos para garantizar tipos de datos correctos.
     */
    protected $casts = [
        'capacidad' => 'integer',
    ];

    /* --- RELACIONES --- */

    /**
     * Una mesa tiene muchos pedidos.
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'mesa_id');
    }
}