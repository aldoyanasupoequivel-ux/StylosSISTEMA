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
        Schema::create('materiales', function (Blueprint $table) {
            $table->id('id_material');
            $table->string('nombre', 150);
            $table->string('descripcion', 255)->nullable();
            $table->string('unidad_medida', 20);
            $table->decimal('stock_actual', 10, 2);
            $table->decimal('stock_minimo', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiales');
    }
};
