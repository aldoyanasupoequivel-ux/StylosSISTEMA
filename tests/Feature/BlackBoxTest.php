<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Administrador;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Material;

class BlackBoxTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    // CP09 y CP10: Consulta pública de pedido
    public function test_consulta_publica_pedido()
    {
        $cliente = Cliente::create(['nombre' => 'Test', 'apellido' => 'Test', 'dni' => '111', 'telefono' => '111']);
        $pedido = Pedido::create(['id_cliente' => $cliente->id_cliente, 'codigo_pedido' => 'PED-123', 'fecha_pedido' => now(), 'fecha_entrega_estimada' => now(), 'estado' => 'Registrado', 'total' => 100]);

        // CP09: Válido
        $response = $this->post('/consulta', ['codigo_pedido' => 'PED-123']);
        $response->assertStatus(200);
        $response->assertSee('PED-123');

        // CP10: Inválido
        $responseInvalid = $this->post('/consulta', ['codigo_pedido' => 'PED-999']);
        $responseInvalid->assertStatus(302);
        $responseInvalid->assertSessionHas('error');
    }

    // CP14: Consultar disponibilidad de material
    public function test_consultar_disponibilidad_material()
    {
        $admin = Administrador::create(['nombre' => 'A', 'apellido' => 'A', 'usuario' => 'admin', 'password' => bcrypt('password')]);
        $this->actingAs($admin);
        
        Material::create(['nombre' => 'Tela Especial', 'unidad_medida' => 'Mts', 'stock_minimo' => 10, 'stock_actual' => 50]);

        $response = $this->get('/materiales');
        $response->assertStatus(200);
        $response->assertSee('Tela Especial');
        $response->assertSee('50'); // Ve el stock
    }

    // CP18 y CP19: Consultar alertas
    public function test_consultar_alertas()
    {
        $admin = Administrador::create(['nombre' => 'A', 'apellido' => 'A', 'usuario' => 'admin', 'password' => bcrypt('password')]);
        $this->actingAs($admin);

        $response = $this->get('/alertas');
        $response->assertStatus(200);
        // Validar que la vista de alertas carga correctamente
        $response->assertViewIs('alertas.index');
    }

    // CP21 y CP22: Cambiar credenciales
    public function test_cambiar_credenciales()
    {
        $admin = Administrador::create(['nombre' => 'A', 'apellido' => 'A', 'usuario' => 'admin', 'password' => bcrypt('password')]);
        $this->actingAs($admin);

        // CP22: Contraseña actual incorrecta
        $responseIncorrecto = $this->put('/cuenta/password', [
            'password_actual' => 'mal_password',
            'password' => 'nueva_password123',
            'password_confirmation' => 'nueva_password123'
        ]);
        $responseIncorrecto->assertSessionHasErrors('password_actual');

        // CP21: Cambio correcto
        $responseCorrecto = $this->put('/cuenta/password', [
            'password_actual' => 'password',
            'password' => 'nueva_password123',
            'password_confirmation' => 'nueva_password123'
        ]);
        $responseCorrecto->assertSessionHas('success');
    }
}
