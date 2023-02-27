<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstudianteFamiliaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estudiante_familiares', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('estudiante_id')->references('id')
                ->on('estudiantes')->onDelete('cascade');
            $table->foreignId('menu_especial')->nullable()->references('id')
                ->on('menus');
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
        Schema::dropIfExists('estudiante_familiares');
    }
}
