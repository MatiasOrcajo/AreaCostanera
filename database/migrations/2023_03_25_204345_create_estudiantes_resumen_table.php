<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EstudiantesResumen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estudiantes_resumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->references('id')
                ->on('estudiantes')->onDelete('cascade');
            $table->unsignedBigInteger('precio_unitario');
            $table->unsignedBigInteger('descuento_egresados');
            $table->unsignedBigInteger('precio_adulto_egresado');
            $table->unsignedBigInteger('menores_12');
            $table->unsignedBigInteger('iva');
            $table->unsignedBigInteger('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
