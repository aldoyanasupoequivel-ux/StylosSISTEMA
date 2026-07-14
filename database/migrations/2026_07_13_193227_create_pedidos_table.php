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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id('id_pedido');
            $table->foreignId('id_cliente')->references('id_cliente')->on('clientes')->onDelete('cascade');
            $table->string('codigo_pedido', 20)->unique();
            $table->date('fecha_pedido');
            $table->date('fecha_entrega_estimada');
            $table->date('fecha_entrega_real')->nullable();
            $table->string('estado', 30);
            $table->text('observaciones')->nullable();
            $table->decimal('total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
