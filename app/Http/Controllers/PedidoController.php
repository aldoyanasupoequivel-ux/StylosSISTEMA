<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\Cliente;
use App\Models\DetallePedido;
use App\Models\Seguimiento;
use Illuminate\Support\Str;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('cliente')->orderBy('id_pedido', 'desc')->paginate(10);
        return view('pedidos.index', compact('pedidos'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        return view('pedidos.create', compact('clientes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:100',
            'dni' => 'required|string|max:20',
            'telefono' => 'required|string|max:20',
            'descripcion' => 'required|string',
            'total' => 'required|numeric|min:0',
            'fecha_entrega_estimada' => 'required|date',
        ]);

        // Separar nombre y apellido si es posible (asumiendo formato "Nombre Apellido")
        $partes = explode(' ', trim($request->nombre_completo), 2);
        $nombre = $partes[0];
        $apellido = isset($partes[1]) ? $partes[1] : '';

        // Buscar cliente por DNI o crear uno nuevo
        $cliente = Cliente::firstOrCreate(
            ['dni' => $request->dni],
            [
                'nombre' => $nombre,
                'apellido' => $apellido,
                'telefono' => $request->telefono,
                'email' => null,
                'direccion' => null,
            ]
        );

        // Generar código único (Ej: PED-ABCD12)
        $codigo = 'PED-' . strtoupper(substr(uniqid(), -6));

        // Crear el pedido
        $pedido = Pedido::create([
            'id_cliente' => $cliente->id_cliente,
            'codigo_pedido' => $codigo,
            'fecha_pedido' => now(),
            'fecha_entrega_estimada' => $request->fecha_entrega_estimada,
            'estado' => 'Registrado',
            'total' => $request->total,
            'observaciones' => null,
        ]);

        // Guardar el detalle único como prenda
        DetallePedido::create([
            'id_pedido' => $pedido->id_pedido,
            'descripcion_prenda' => $request->descripcion,
            'cantidad' => 1,
            'precio_unitario' => $request->total,
            'subtotal' => $request->total,
        ]);

        // Registrar seguimiento inicial
        Seguimiento::create([
            'id_pedido' => $pedido->id_pedido,
            'estado' => 'Registrado',
            'porcentaje_avance' => 0,
            'fecha_actualizacion' => now(),
        ]);

        // Registrar pago inicial si se proporcionó adelanto
        if ($request->filled('adelanto') && $request->adelanto > 0) {
            \App\Models\Pago::create([
                'id_pedido' => $pedido->id_pedido,
                'monto' => $request->adelanto,
                'fecha_pago' => now(),
                'metodo_pago' => 'Adelanto inicial',
            ]);
        }

        return redirect()->route('pedidos.index')->with('success', 'Pedido creado exitosamente con el código: ' . $codigo);
    }

    public function show(Pedido $pedido)
    {
        $pedido->load(['cliente', 'detalles', 'pagos', 'seguimientos' => function($q) {
            $q->orderBy('fecha_actualizacion', 'desc');
        }]);
        return view('pedidos.show', compact('pedido'));
    }

    public function edit(Pedido $pedido)
    {
        return view('pedidos.edit', compact('pedido'));
    }

    public function update(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado' => 'required|string',
            'fecha_entrega_real' => 'nullable|date',
            'observaciones' => 'nullable|string',
            'porcentaje_avance' => 'nullable|integer|min:0|max:100',
        ]);

        $pedido->update($request->only('estado', 'fecha_entrega_real', 'observaciones'));

        if ($request->estado != 'Registrado' && $request->filled('porcentaje_avance')) {
            Seguimiento::create([
                'id_pedido' => $pedido->id_pedido,
                'fecha_actualizacion' => now(),
                'estado' => $request->estado,
                'porcentaje_avance' => $request->porcentaje_avance,
            ]);
        }

        return redirect()->route('pedidos.show', $pedido->id_pedido)->with('success', 'Pedido actualizado correctamente.');
    }

    public function destroy(Pedido $pedido)
    {
        $pedido->delete();
        return redirect()->route('pedidos.index')->with('success', 'Pedido eliminado.');
    }
}
