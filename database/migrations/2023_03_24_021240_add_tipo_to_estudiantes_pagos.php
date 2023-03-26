<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoToEstudiantesPagos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estudiantes_pagos', function (Blueprint $table) {
            $table->text('tipo')->nullable();
            $table->foreignId('estudiantes_cuotas_id')->nullable()->references('id')->on('estudiantes_cuotas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estudiantes_pagos', function (Blueprint $table) {
            //
        });
    }
}
