<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'categoria_id',
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'estado',
    ];

    /**
     * Casteo de atributos para garantizar tipos de datos correctos.
     */
    protected $casts = [
        'precio' => 'decimal:2',
        'estado' => 'boolean',
    ];

    /**
     * Accessor para obtener la URL completa de la imagen o una por defecto.
     */
    public function getImagenUrlAttribute(): string
    {
        if ($this->imagen && Storage::disk('public')->exists($this->imagen)) {
            return Storage::url($this->imagen);
        }

        return asset('images/no-image.png'); // Imagen por defecto si no sube foto
    }

    /* --- RELACIONES --- */

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'producto_id');
    }
}