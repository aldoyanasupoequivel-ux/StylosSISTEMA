<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Pedido;

class PagoController extends Controller
{
    public function store(Request $request, Pedido $pedido)
    {
        $request->validate([
            'monto' => 'required|numeric|min:0.01',
            'fecha_pago' => 'required|date',
            'metodo_pago' => 'nullable|string|max:30',
        ]);

        $pagosActuales = $pedido->pagos()->sum('monto');
        $saldo = $pedido->total - $pagosActuales;

        if ($request->monto > $saldo) {
            return back()->with('error', 'El monto ingresado excede el saldo pendiente (S/ ' . number_format($saldo, 2) . ').');
        }

        Pago::create([
            'id_pedido' => $pedido->id_pedido,
            'monto' => $request->monto,
            'fecha_pago' => $request->fecha_pago,
            'metodo_pago' => $request->metodo_pago,
        ]);

        return back()->with('success', 'Pago registrado exitosamente.');
    }
}
