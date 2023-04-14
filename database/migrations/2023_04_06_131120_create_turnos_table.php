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
            //relacion con cancha
            $table->integer('id_cancha');
            $table->foreign('id_cancha')->references('id')->on('canchas')->onUpdate('cascade')->onDelete('cascade');
            UNIQUE('fecha_turno','hora_turno','id_cancha');
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
