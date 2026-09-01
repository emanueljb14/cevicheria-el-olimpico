<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventarios';

    protected $fillable = [
        'nombre_insumo',
        'stock',
        'unidad_medida',
        'stock_minimo',
    ];

    protected $casts = [
        'stock'        => 'decimal:2',
        'stock_minimo' => 'decimal:2',
    ];

    /**
     * Accessor para saber si el insumo está con stock crítico.
     */
    public function getBajoStockAttribute(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }
}