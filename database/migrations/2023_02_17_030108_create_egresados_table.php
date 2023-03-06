<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEgresadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('egresados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('escuela_id')->references('id')
                ->on('escuelas')->onDelete('cascade');
            $table->foreignId('menu_id')->references('id')
                ->on('menus');
            $table->foreignId('dia_id')->references('id')
                ->on('dias');
            $table->text('curso');
            $table->unsignedBigInteger('cantidad_egresados');
            $table->text('fecha');
            $table->text('fecha_carbon');// event date
//            $table->text('fecha_pago');
//            $table->foreignId('forma_pago_id')->references('id')
//                ->on('formas_pago');
            $table->boolean('status')->default(1);
//            $table->boolean('esta_pago')->default(0);
            $table->string('slug');
//            $table->integer('egresados_totales');
//            $table->integer('total')->nullable();
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
        Schema::dropIfExists('egresados');
    }
}
