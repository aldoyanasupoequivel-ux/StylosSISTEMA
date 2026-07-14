<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Administrador;
use App\Models\Cliente;
use App\Models\Pedido;

class PedidoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Log in to access protected routes (assuming auth is required for these)
        $admin = Administrador::create([
            'nombre' => 'Admin',
            'apellido' => 'Test',
            'usuario' => 'admin',
            'correo' => 'admin@test.com',
            'telefono' => '123456789',
            'password' => bcrypt('password'),
        ]);
        $this->actingAs($admin);
    }

    /**
     * PU03. Registro de un pedido válido.
     */
    public function test_registro_de_un_pedido_valido(): void
    {
        // ARRANGE: Preparar datos
        $datosPedido = [
            'nombre_completo' => 'Juan Perez',
            'dni' => '12345678',
            'telefono' => '987654321',
            'descripcion' => 'Traje a medida',
            'total' => 500.00,
            'fecha_entrega_estimada' => now()->addDays(7)->format('Y-m-d'),
            'adelanto' => 100.00,
        ];

        // ACT: Ejecutar la operación (post a la ruta store de pedidos)
        $response = $this->post(route('pedidos.store'), $datosPedido);

        // ASSERT: Verificar el resultado
        $response->assertRedirect(route('pedidos.index'));
        $response->assertSessionHas('success');

        // Verificar que el cliente fue creado
        $this->assertDatabaseHas('clientes', [
            'dni' => '12345678',
            'nombre' => 'Juan',
            'apellido' => 'Perez',
        ]);

        // Verificar que el pedido fue creado
        $cliente = Cliente::where('dni', '12345678')->first();
        $this->assertDatabaseHas('pedidos', [
            'id_cliente' => $cliente->id_cliente,
            'total' => 500.00,
            'estado' => 'Registrado',
        ]);

        $pedido = Pedido::where('id_cliente', $cliente->id_cliente)->first();

        // Verificar detalle
        $this->assertDatabaseHas('detalle_pedidos', [
            'id_pedido' => $pedido->id_pedido,
            'descripcion_prenda' => 'Traje a medida',
        ]);

        // Verificar seguimiento inicial
        $this->assertDatabaseHas('seguimientos', [
            'id_pedido' => $pedido->id_pedido,
            'estado' => 'Registrado',
            'porcentaje_avance' => 0,
        ]);

        // Verificar pago (adelanto)
        $this->assertDatabaseHas('pagos', [
            'id_pedido' => $pedido->id_pedido,
            'monto' => 100.00,
        ]);
    }

    /**
     * PU05. Validación del porcentaje de avance.
     */
    public function test_validacion_del_porcentaje_de_avance(): void
    {
        // ARRANGE: Crear cliente y pedido
        $cliente = Cliente::create([
            'nombre' => 'Ana',
            'apellido' => 'Gomez',
            'dni' => '87654321',
            'telefono' => '999888777',
        ]);

        $pedido = Pedido::create([
            'id_cliente' => $cliente->id_cliente,
            'codigo_pedido' => 'PED-TEST01',
            'fecha_pedido' => now(),
            'fecha_entrega_estimada' => now()->addDays(5),
            'estado' => 'Registrado',
            'total' => 300.00,
        ]);

        // ACT: Intentar actualizar con porcentaje inválido (mayor a 100)
        $responseInvalido = $this->put(route('pedidos.update', $pedido->id_pedido), [
            'estado' => 'En Proceso',
            'porcentaje_avance' => 150, // Inválido
        ]);

        // ASSERT: Verificar rechazo
        $responseInvalido->assertSessionHasErrors('porcentaje_avance');
        $this->assertDatabaseMissing('seguimientos', [
            'id_pedido' => $pedido->id_pedido,
            'porcentaje_avance' => 150,
        ]);

        // ACT: Intentar actualizar con porcentaje válido (entre 0 y 100)
        $responseValido = $this->put(route('pedidos.update', $pedido->id_pedido), [
            'estado' => 'En Costura',
            'porcentaje_avance' => 50, // Válido
        ]);

        // ASSERT: Verificar aceptación
        $responseValido->assertRedirect(route('pedidos.show', $pedido->id_pedido));
        $responseValido->assertSessionHas('success');
        $this->assertDatabaseHas('seguimientos', [
            'id_pedido' => $pedido->id_pedido,
            'estado' => 'En Costura',
            'porcentaje_avance' => 50,
        ]);
    }
}
