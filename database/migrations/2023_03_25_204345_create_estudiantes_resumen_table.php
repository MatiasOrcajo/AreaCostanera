<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstudiantesResumenTable extends Migration
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
            $table->unsignedBigInteger('precio_unitario_descuentos');
            $table->unsignedBigInteger('descuento_estudiante');
            $table->unsignedBigInteger('descuento_cantidad_egresados');
            $table->unsignedBigInteger('descuento_dia_elegido');
            $table->unsignedBigInteger('interes_cuotas');
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
