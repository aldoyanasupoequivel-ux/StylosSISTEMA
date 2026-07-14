<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';

    protected $fillable = [
        'id_cliente',
        'codigo_pedido',
        'fecha_pedido',
        'fecha_entrega_estimada',
        'fecha_entrega_real',
        'estado',
        'observaciones',
        'total',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'id_cliente', 'id_cliente');
    }

    public function detalles()
    {
        return $this->hasMany(DetallePedido::class, 'id_pedido', 'id_pedido');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_pedido', 'id_pedido');
    }

    public function seguimientos()
    {
        return $this->hasMany(Seguimiento::class, 'id_pedido', 'id_pedido');
    }
}
