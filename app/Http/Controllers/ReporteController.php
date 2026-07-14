<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        // Default dates: first and last day of current month
        $fechaInicio = $request->input('fecha_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $fechaFin = $request->input('fecha_fin', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $tab = $request->input('tab', 'ingresos');

        // --- LÓGICA PARA REPORTE DE INGRESOS (Pagos) ---
        $pagosQuery = \App\Models\Pago::with(['pedido.cliente'])
            ->whereBetween('fecha_pago', [
                $fechaInicio . ' 00:00:00',
                $fechaFin . ' 23:59:59'
            ])
            ->orderBy('fecha_pago', 'desc');

        $pagos = $pagosQuery->get();
        $totalIngresos = $pagos->sum('monto');
        $cantidadPagos = $pagos->count();

        // --- LÓGICA PARA REPORTE DE PEDIDOS ---
        $pedidosQuery = Pedido::whereBetween('fecha_pedido', [
                $fechaInicio . ' 00:00:00',
                $fechaFin . ' 23:59:59'
            ]);

        $pedidosCount = $pedidosQuery->count();
        // Obtener counts por estado
        $estadosCount = $pedidosQuery->selectRaw('estado, count(*) as total')
            ->groupBy('estado')
            ->pluck('total', 'estado')
            ->toArray();

        $statsPedidos = [
            'Pendientes' => $estadosCount['Registrado'] ?? 0, // Usaremos Registrado como Pendiente
            'En Proceso' => ($estadosCount['En Corte'] ?? 0) + ($estadosCount['En Costura'] ?? 0) + ($estadosCount['En Acabados'] ?? 0),
            'Listos entrega' => $estadosCount['Finalizado'] ?? 0,
            'Entregados' => $estadosCount['Entregado'] ?? 0,
            'Cancelados' => $estadosCount['Cancelado'] ?? 0,
        ];
        $statsPedidos['Total pedidos'] = array_sum($statsPedidos);

        return view('reportes.index', compact(
            'fechaInicio', 'fechaFin', 'tab',
            'pagos', 'totalIngresos', 'cantidadPagos',
            'statsPedidos'
        ));
    }
}
