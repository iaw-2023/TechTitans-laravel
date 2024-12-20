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
        Schema::create('canchas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',50)->nullable();
            $table->float('precio')->nullable();
            $table->boolean('techo');
            $table->integer('cant_jugadores')->nullable();
            $table->string('superficie')->nullable();
            $table->timestamps();
            //relacion con categoria
            $table->integer('id_categoria');
            $table->foreign('id_categoria')->references('id')->on('categorias')->onUpdate('cascade')->onDelete('restrict');
            $table->boolean('activo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canchas');
    }
};
