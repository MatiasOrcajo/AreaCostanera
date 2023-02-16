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
            $table->text('curso');
            $table->date('fecha');
            $table->date('fecha_pago');
            $table->unsignedBigInteger('forma_pago');
            $table->boolean('status');
            $table->string('slug');
            $table->integer('total');
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
