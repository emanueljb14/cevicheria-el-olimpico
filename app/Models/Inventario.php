<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventarios';

    protected $fillable = [
        'producto_id',
        'nombre_insumo',
        'stock',
        'unidad_medida',
        'stock_minimo',
        'precio_unitario',
        'estado',
    ];

    protected $casts = [
        'stock'           => 'decimal:2',
        'stock_minimo'    => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'estado'          => 'boolean',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}