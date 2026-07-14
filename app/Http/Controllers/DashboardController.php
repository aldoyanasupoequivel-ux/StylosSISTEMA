<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Material;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPedidos = Pedido::count();
        $ingresosMes = Pedido::whereMonth('fecha_pedido', Carbon::now()->month)
                             ->whereYear('fecha_pedido', Carbon::now()->year)
                             ->sum('total');
                             
        $pedidosEnProceso = Pedido::whereNotIn('estado', ['Entregado', 'Finalizado'])->count();
        
        $alertasStock = Material::whereColumn('stock_actual', '<=', 'stock_minimo')->count();
        
        $pedidosRecientes = Pedido::with('cliente')->orderBy('fecha_pedido', 'desc')->take(5)->get();

        return view('dashboard', compact(
            'totalPedidos', 
            'ingresosMes', 
            'pedidosEnProceso', 
            'alertasStock',
            'pedidosRecientes'
        ));
    }
}
