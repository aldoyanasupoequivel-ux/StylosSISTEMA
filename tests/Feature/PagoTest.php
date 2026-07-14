<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Administrador;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Pago;

class PagoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
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
     * PU04. Cálculo de pagos y saldo pendiente.
     */
    public function test_calculo_de_pagos_y_saldo_pendiente(): void
    {
        // ARRANGE: Crear cliente y pedido con un total de 1000
        $cliente = Cliente::create([
            'nombre' => 'Ana',
            'apellido' => 'Gomez',
            'dni' => '87654321',
            'telefono' => '999888777',
        ]);

        $pedido = Pedido::create([
            'id_cliente' => $cliente->id_cliente,
            'codigo_pedido' => 'PED-TEST02',
            'fecha_pedido' => now(),
            'fecha_entrega_estimada' => now()->addDays(5),
            'estado' => 'Registrado',
            'total' => 1000.00,
        ]);

        // Ya tiene 0 pagos, saldo es 1000. Hacemos un pago válido de 400.
        $this->post(route('pagos.store', $pedido->id_pedido), [
            'monto' => 400.00,
            'fecha_pago' => now()->format('Y-m-d'),
            'metodo_pago' => 'Efectivo',
        ]);

        // Verificamos que se insertó el pago
        $this->assertDatabaseHas('pagos', [
            'id_pedido' => $pedido->id_pedido,
            'monto' => 400.00,
        ]);

        // ACT: Intentar hacer un pago que exceda el saldo pendiente (Saldo = 600, intento 700)
        $responseExcede = $this->post(route('pagos.store', $pedido->id_pedido), [
            'monto' => 700.00,
            'fecha_pago' => now()->format('Y-m-d'),
            'metodo_pago' => 'Tarjeta',
        ]);

        // ASSERT: Verificar que fue rechazado
        $responseExcede->assertSessionHas('error'); // El controlador envía un 'error' si excede el saldo
        $this->assertDatabaseMissing('pagos', [
            'monto' => 700.00,
        ]);
    }

    /**
     * PU10. Cálculo del reporte de ingresos.
     */
    public function test_calculo_del_reporte_de_ingresos(): void
    {
        // ARRANGE: Crear pedidos y pagos en el mes actual
        $cliente = Cliente::create([
            'nombre' => 'Luis',
            'apellido' => 'Perez',
            'dni' => '11223344',
            'telefono' => '999111222',
        ]);

        $pedido1 = Pedido::create([
            'id_cliente' => $cliente->id_cliente,
            'codigo_pedido' => 'PED-TEST03',
            'fecha_pedido' => now(),
            'fecha_entrega_estimada' => now()->addDays(5),
            'estado' => 'Registrado',
            'total' => 500.00, // Total no es igual a ingresos, el ingreso depende de los pagos
        ]);

        $pedido2 = Pedido::create([
            'id_cliente' => $cliente->id_cliente,
            'codigo_pedido' => 'PED-TEST04',
            'fecha_pedido' => now(),
            'fecha_entrega_estimada' => now()->addDays(5),
            'estado' => 'Registrado',
            'total' => 800.00,
        ]);

        // Ingresos reales (Pagos): 200 + 300 = 500
        Pago::create([
            'id_pedido' => $pedido1->id_pedido,
            'monto' => 200.00,
            'fecha_pago' => now(),
            'metodo_pago' => 'Efectivo',
        ]);

        Pago::create([
            'id_pedido' => $pedido2->id_pedido,
            'monto' => 300.00,
            'fecha_pago' => now(),
            'metodo_pago' => 'Efectivo',
        ]);

        // ACT: Llamar a la ruta de reportes
        $response = $this->get(route('reportes.index'));

        // ASSERT: Verificar que el total de ingresos enviado a la vista sea 500 (la suma de los pagos, no de los pedidos)
        $response->assertStatus(200);
        $response->assertViewHas('totalIngresos', function ($totalIngresos) {
            return $totalIngresos == 500.00;
        });
        $response->assertViewHas('cantidadPagos', 2);
    }
}
