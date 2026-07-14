<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Administrador;
use App\Models\Material;

class InventarioTest extends TestCase
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
     * PU06. Registro de entrada de inventario.
     */
    public function test_registro_de_entrada_de_inventario(): void
    {
        // ARRANGE
        $material = Material::create([
            'nombre' => 'Tela Algodon',
            'descripcion' => 'Tela de algodon',
            'unidad_medida' => 'Metros',
            'stock_minimo' => 10,
            'stock_actual' => 50,
        ]);

        // ACT: Entrada de 20 metros
        $response = $this->post(route('movimientos.store', $material->id_material), [
            'tipo_movimiento' => 'Entrada',
            'cantidad' => 20,
            'observacion' => 'Compra',
        ]);

        // ASSERT
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('movimientos_inventario', [
            'id_material' => $material->id_material,
            'tipo_movimiento' => 'Entrada',
            'cantidad' => 20,
        ]);
        
        // Verificar que el stock aumentó a 70
        $this->assertEquals(70, $material->fresh()->stock_actual);
    }

    /**
     * PU07. Registro de salida de inventario.
     */
    public function test_registro_de_salida_de_inventario(): void
    {
        // ARRANGE
        $material = Material::create([
            'nombre' => 'Hilo Azul',
            'unidad_medida' => 'Conos',
            'stock_minimo' => 5,
            'stock_actual' => 20,
        ]);

        // ACT: Salida de 5 conos
        $response = $this->post(route('movimientos.store', $material->id_material), [
            'tipo_movimiento' => 'Salida',
            'cantidad' => 5,
            'observacion' => 'Uso en taller',
        ]);

        // ASSERT
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('movimientos_inventario', [
            'id_material' => $material->id_material,
            'tipo_movimiento' => 'Salida',
            'cantidad' => 5,
        ]);
        
        // Verificar que el stock disminuyó a 15
        $this->assertEquals(15, $material->fresh()->stock_actual);
    }

    /**
     * PU08. Validación de stock insuficiente.
     */
    public function test_validacion_de_stock_insuficiente(): void
    {
        // ARRANGE
        $material = Material::create([
            'nombre' => 'Botones',
            'unidad_medida' => 'Unidades',
            'stock_minimo' => 50,
            'stock_actual' => 10, // Solo hay 10
        ]);

        // ACT: Intentar sacar 15
        $response = $this->post(route('movimientos.store', $material->id_material), [
            'tipo_movimiento' => 'Salida',
            'cantidad' => 15,
            'observacion' => 'Uso en taller',
        ]);

        // ASSERT
        $response->assertSessionHas('error'); // El controlador rechaza la salida
        
        // El movimiento no se registra
        $this->assertDatabaseMissing('movimientos_inventario', [
            'id_material' => $material->id_material,
            'tipo_movimiento' => 'Salida',
            'cantidad' => 15,
        ]);
        
        // El stock no se modifica (sigue siendo 10)
        $this->assertEquals(10, $material->fresh()->stock_actual);
    }

    /**
     * PU09. Identificación de material con stock bajo.
     */
    public function test_identificacion_de_material_con_stock_bajo(): void
    {
        // ARRANGE: Crear materiales con stock normal y bajo
        Material::create([
            'nombre' => 'Material Normal',
            'unidad_medida' => 'Mts',
            'stock_minimo' => 10,
            'stock_actual' => 20, // Mayor al mínimo
        ]);

        $materialBajo1 = Material::create([
            'nombre' => 'Material Critico',
            'unidad_medida' => 'Mts',
            'stock_minimo' => 10,
            'stock_actual' => 5, // Menor al mínimo
        ]);

        $materialBajo2 = Material::create([
            'nombre' => 'Material Exacto',
            'unidad_medida' => 'Mts',
            'stock_minimo' => 10,
            'stock_actual' => 10, // Igual al mínimo (cuenta como bajo según el controlador de Alertas)
        ]);

        // ACT: Filtrar materiales con stock bajo (simulando la lógica del AlertaController)
        $materialesBajos = Material::whereColumn('stock_actual', '<=', 'stock_minimo')->get();

        // ASSERT
        $this->assertCount(2, $materialesBajos);
        $this->assertTrue($materialesBajos->contains('id_material', $materialBajo1->id_material));
        $this->assertTrue($materialesBajos->contains('id_material', $materialBajo2->id_material));
    }
}
