<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovimientoInventario;
use App\Models\Material;

class MovimientoInventarioController extends Controller
{
    public function store(Request $request, Material $material)
    {
        $request->validate([
            'tipo_movimiento' => 'required|in:Entrada,Salida',
            'cantidad' => 'required|numeric|min:0.01',
            'observacion' => 'nullable|string|max:100',
        ]);

        if ($request->tipo_movimiento == 'Salida' && $material->stock_actual < $request->cantidad) {
            return back()->with('error', 'Stock insuficiente para realizar la salida. El stock actual es: ' . $material->stock_actual);
        }

        // Registrar movimiento
        MovimientoInventario::create([
            'id_material' => $material->id_material,
            'id_administrador' => auth()->id() ?? 1,
            'tipo_movimiento' => $request->tipo_movimiento,
            'cantidad' => $request->cantidad,
            'fecha_movimiento' => now(),
            'observacion' => $request->observacion,
        ]);

        // Actualizar stock de material
        if ($request->tipo_movimiento == 'Entrada') {
            $material->stock_actual += $request->cantidad;
        } else {
            $material->stock_actual -= $request->cantidad;
        }
        $material->save();

        return back()->with('success', 'Movimiento registrado correctamente. Stock actualizado.');
    }
}
