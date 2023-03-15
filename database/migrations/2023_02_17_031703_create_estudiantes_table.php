<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstudiantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('egresado_id')->references('id')
                ->on('egresados')->onDelete('cascade');
//            $table->foreignId('menu_id')->references('id')
//                ->on('menus');
            $table->foreignId('menu_especial_id')->nullable()->references('id')
                ->on('menus_especiales');
            $table->date('fecha_pago');
            $table->foreignId('medio_pago_id')->references('id')
                ->on('medios_pago');
            $table->foreignId('forma_pago_id')->references('id')
                ->on('formas_pago');
            $table->integer('familiares')->nullable();
            $table->integer('menores_12')->nullable();
            $table->integer('menores_5')->nullable();
            $table->text('email')->nullable();
            $table->text('telefono')->nullable();
            //$table->integer('total');
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
        Schema::dropIfExists('estudiantes');
    }
}
