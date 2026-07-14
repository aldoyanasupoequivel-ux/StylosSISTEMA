<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;

class MaterialController extends Controller
{
    public function index()
    {
        $materiales = Material::orderBy('nombre')->paginate(15);
        return view('materiales.index', compact('materiales'));
    }

    public function create()
    {
        return view('materiales.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_material' => 'required|string|max:150',
            'unidad_medida' => 'required|string|max:20',
            'stock_minimo' => 'required|numeric|min:0',
            'stock_actual' => 'required|numeric|min:0',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $material = Material::create([
            'nombre' => $request->nombre_material,
            'unidad_medida' => $request->unidad_medida,
            'stock_minimo' => $request->stock_minimo,
            'stock_actual' => $request->stock_actual,
            'descripcion' => $request->descripcion,
        ]);

        if ($request->stock_actual > 0) {
            \App\Models\MovimientoInventario::create([
                'id_material' => $material->id_material,
                'id_administrador' => auth()->id() ?? 1, // Fallback a 1 por seguridad
                'tipo_movimiento' => 'Entrada',
                'cantidad' => $request->stock_actual,
                'fecha_movimiento' => now(),
                'observacion' => 'Stock inicial',
            ]);
        }

        return redirect()->route('materiales.index')->with('success', 'Material registrado correctamente.');
    }

    public function show(Material $materiale) // Laravel usa singulares por defecto, pero la ruta es materiales
    {
        $material = $materiale; // Renombrar para claridad
        $movimientos = $material->movimientos()->orderBy('fecha_movimiento', 'desc')->paginate(10);
        return view('materiales.show', compact('material', 'movimientos'));
    }

    public function edit(Material $materiale)
    {
        $material = $materiale;
        return view('materiales.edit', compact('material'));
    }

    public function update(Request $request, Material $materiale)
    {
        $material = $materiale;
        
        $request->validate([
            'nombre_material' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:255',
            'unidad_medida' => 'required|string|max:20',
            'stock_minimo' => 'required|numeric|min:0',
        ]);

        $material->update([
            'nombre' => $request->nombre_material,
            'descripcion' => $request->descripcion,
            'unidad_medida' => $request->unidad_medida,
            'stock_minimo' => $request->stock_minimo,
        ]);

        return redirect()->route('materiales.index')->with('success', 'Material actualizado.');
    }

    public function destroy(Material $materiale)
    {
        $materiale->delete();
        return redirect()->route('materiales.index')->with('success', 'Material eliminado.');
    }
}
