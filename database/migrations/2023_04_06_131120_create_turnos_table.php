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
        Schema::create('turnos', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_turno');
            $table->time('hora_turno');
            $table->timestamps();
            $table->integer('id_cancha');
            //relacion con cancha
            $table->foreign('id_cancha')->references('id')->on('canchas')->onUpdate('cascade')->onDelete('restrict');
            // Restricción única en las columnas "fecha_turno", "hora_turno" y "id_cancha"
            $table->unique(['fecha_turno', 'hora_turno', 'id_cancha']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnos');
    }
};
