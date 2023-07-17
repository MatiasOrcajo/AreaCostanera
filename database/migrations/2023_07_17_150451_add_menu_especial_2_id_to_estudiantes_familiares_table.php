<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuEspecial2IdToEstudiantesFamiliaresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estudiante_familiares', function (Blueprint $table) {
            $table->foreignId('menu_especial_2_id')->after('menu_especial')->nullable()->references('id')->on('menus_especiales');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estudiantes_familiares', function (Blueprint $table) {
            //
        });
    }
}
