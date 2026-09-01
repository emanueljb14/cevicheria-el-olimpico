<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'pedido_id',
        'pago_id',
        'monto_total',
        'fecha',
    ];

    protected $casts = [
        'monto_total' => 'decimal:2',
        'fecha'       => 'datetime',
    ];

    /* --- RELACIONES --- */

    /**
     * Una venta pertenece a un pedido.
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    /**
     * Una venta corresponde a un pago registrado.
     */
    public function pago()
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }
}