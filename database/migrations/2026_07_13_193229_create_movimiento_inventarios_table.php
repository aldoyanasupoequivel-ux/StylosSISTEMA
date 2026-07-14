<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('movimientos_inventario', function (Blueprint $table) {
            $table->id('id_movimiento');
            $table->foreignId('id_material')->references('id_material')->on('materiales')->onDelete('cascade');
            $table->foreignId('id_administrador')->references('id_administrador')->on('administradores')->onDelete('cascade');
            $table->string('tipo_movimiento', 20);
            $table->decimal('cantidad', 10, 2);
            $table->dateTime('fecha_movimiento');
            $table->string('observacion', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos_inventario');
    }
};
