<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CuentaController extends Controller
{
    public function mostrar()
    {
        $admin = Auth::user();
        return view('cuenta.index', compact('admin'));
    }

    public function actualizarUsuario(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:50|unique:administradores,usuario,' . Auth::id() . ',id_administrador',
        ]);

        $admin = Auth::user();
        $admin->usuario = $request->usuario;
        $admin->save();

        return redirect()->route('cuenta.index')->with('success', 'Nombre de usuario actualizado correctamente.');
    }

    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $admin = Auth::user();

        if (!Hash::check($request->password_actual, $admin->password)) {
            return back()->withErrors(['password_actual' => 'La contraseña actual no es correcta.']);
        }

        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()->route('cuenta.index')->with('success', 'Contraseña actualizada correctamente.');
    }
}
