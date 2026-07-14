<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\Administrador;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * PU01. Inicio de sesión con credenciales válidas.
     */
    public function test_administrador_puede_iniciar_sesion_con_credenciales_validas(): void
    {
        // ARRANGE: Preparar datos
        $administrador = Administrador::create([
            'nombre' => 'Admin Test',
            'apellido' => 'Test',
            'usuario' => 'admin_test',
            'correo' => 'admin@test.com',
            'telefono' => '123456789',
            'password' => Hash::make('password123'),
        ]);

        // ACT: Ejecutar la operación
        $response = $this->post('/login', [
            'usuario' => 'admin_test',
            'password' => 'password123',
        ]);

        // ASSERT: Verificar el resultado
        $this->assertAuthenticatedAs($administrador);
        $response->assertRedirect('/dashboard');
    }

    /**
     * PU02. Rechazo de credenciales incorrectas.
     */
    public function test_rechazo_de_credenciales_incorrectas(): void
    {
        // ARRANGE: Preparar datos
        Administrador::create([
            'nombre' => 'Admin Test',
            'apellido' => 'Test',
            'usuario' => 'admin_test',
            'correo' => 'admin@test.com',
            'telefono' => '123456789',
            'password' => Hash::make('password123'),
        ]);

        // ACT: Ejecutar la operación con clave incorrecta
        $response = $this->post('/login', [
            'usuario' => 'admin_test',
            'password' => 'clave_incorrecta',
        ]);

        // ASSERT: Verificar el resultado
        $this->assertGuest();
        $response->assertSessionHasErrors('usuario'); // Validar que devuelve error en el campo usuario (así está en AuthController)
    }
}
