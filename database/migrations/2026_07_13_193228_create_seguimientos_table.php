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
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->id('id_seguimiento');
            $table->foreignId('id_pedido')->references('id_pedido')->on('pedidos')->onDelete('cascade');
            $table->dateTime('fecha_actualizacion');
            $table->string('estado', 30);
            $table->integer('porcentaje_avance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimientos');
    }
};
