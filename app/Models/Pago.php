<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'pedido_id',
        'metodo',
        'monto',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
    ];

    /* --- RELACIONES --- */

    /**
     * Un pago pertenece a un pedido.
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    /**
     * Un pago genera una única venta.
     */
    public function venta()
    {
        return $this->hasOne(Venta::class, 'pago_id');
    }
}