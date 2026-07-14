<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdministradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Administrador::create([
            'nombre' => 'Administrador',
            'apellido' => 'Principal',
            'usuario' => 'admin',
            'correo' => 'admin@stylos.com',
            'password' => \Illuminate\Support\Facades\Hash::make('12345678'),
        ]);
    }
}
