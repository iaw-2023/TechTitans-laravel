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
        Schema::create('detalle_reservas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->float('precio');
             //relacion con reservas
             $table->integer('id_reserva');
             $table->foreign('id_reserva')->references('id')->on('reservas')->onUpdate('cascade')->onDelete('cascade');
             //relacion con turnos
             $table->integer('id_turno');
             $table->foreign('id_turno')->references('id')->on('turnos')->onUpdate('cascade')->onDelete('restrict');
            $table->boolean('cancelado')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_reservas');
    }
};
