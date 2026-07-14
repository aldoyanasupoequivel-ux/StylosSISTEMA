<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seguimiento extends Model
{
    use HasFactory;

    protected $table = 'seguimientos';
    protected $primaryKey = 'id_seguimiento';

    protected $fillable = [
        'id_pedido',
        'fecha_actualizacion',
        'estado',
        'porcentaje_avance',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }
}
