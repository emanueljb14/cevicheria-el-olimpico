<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reporte extends Model
{
    use HasFactory;

    protected $table = 'reportes';

    protected $fillable = [
        'titulo',
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'monto_total',
        'user_id',
        'archivo_pdf',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
        'monto_total'  => 'decimal:2',
    ];

    /* --- RELACIONES --- */

    /**
     * Reporte generado por un usuario registrado.
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}