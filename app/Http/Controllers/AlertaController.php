<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Material;
use Carbon\Carbon;

class AlertaController extends Controller
{
    public function index(Request $request)
    {
        // Obtener el número de días del filtro, por defecto 3, cast a int
        $dias = (int) $request->input('dias', 3);

        $fechaHoy = Carbon::today();
        $fechaLimite = Carbon::today()->addDays($dias);

        // Pedidos próximos a vencer
        // Excluir pedidos Finalizados y Entregados
        $pedidosProximos = Pedido::with('cliente')
            ->whereNotIn('estado', ['Finalizado', 'Entregado'])
            ->whereDate('fecha_entrega_estimada', '>=', $fechaHoy)
            ->whereDate('fecha_entrega_estimada', '<=', $fechaLimite)
            ->orderBy('fecha_entrega_estimada', 'asc')
            ->get();

        // Materiales con stock bajo (stock actual <= stock minimo)
        $materialesBajos = Material::whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderBy('stock_actual', 'asc')
            ->get();

        return view('alertas.index', compact('pedidosProximos', 'materialesBajos', 'dias'));
    }
}
