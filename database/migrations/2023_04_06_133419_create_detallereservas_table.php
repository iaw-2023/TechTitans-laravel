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
        Schema::create('detallereservas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->float('precio');

             //relacion con reservas

             $table->integer('id_reserva');
             $table->foreign('id_reserva')->references('id')->on('reservas')->onUpdate('cascade')->onDelete('cascade');

             //relacion con turnos

             $table->integer('id_turno');
             $table->foreign('id_turno')->references('id')->on('turnos')->onUpdate('cascade')->onDelete('cascade');
 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detallereservas');
    }
};
