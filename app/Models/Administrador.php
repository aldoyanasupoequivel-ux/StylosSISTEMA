<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Administrador extends Authenticatable
{
    use HasFactory;

    protected $table = 'administradores';
    protected $primaryKey = 'id_administrador';

    protected $fillable = [
        'nombre',
        'apellido',
        'usuario',
        'correo',
        'telefono',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function movimientosInventario()
    {
        return $this->hasMany(MovimientoInventario::class, 'id_administrador', 'id_administrador');
    }
}
