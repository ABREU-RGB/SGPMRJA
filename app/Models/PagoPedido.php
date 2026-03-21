<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoPedido extends Model
{
    use HasFactory;

    protected $table = 'pago_pedido';

    protected $fillable = [
        'pedido_id',
        'metodo',
        'monto',
        'banco_id',
        'referencia',
    ];

    protected $casts = [
        'monto' => 'decimal:2',
    ];

    const METODOS = ['efectivo', 'transferencia', 'pago_movil'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }
}
