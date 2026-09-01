<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    /**
     * Casteo de atributos para garantizar tipos de datos correctos.
     */
    protected $casts = [
        'estado' => 'boolean',
    ];

    /* --- RELACIONES --- */

    /**
     * Una categoría tiene muchos productos.
     */
    public function productos()
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }
}