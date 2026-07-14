<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;

class ConsultaController extends Controller
{
    public function index()
    {
        return view('consulta.index');
    }

    public function buscar(Request $request)
    {
        $request->validate([
            'codigo_pedido' => 'required|string|max:20',
        ]);

        $pedido = Pedido::with(['cliente', 'detalles', 'seguimientos' => function($q) {
            $q->orderBy('fecha_actualizacion', 'desc');
        }])->where('codigo_pedido', $request->codigo_pedido)->first();

        if (!$pedido) {
            return back()->with('error', 'No se encontró ningún pedido con el código proporcionado.');
        }

        return view('consulta.index', compact('pedido'));
    }
}
