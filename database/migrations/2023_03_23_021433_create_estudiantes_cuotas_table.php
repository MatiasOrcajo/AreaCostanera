<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstudiantesCuotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estudiantes_cuotas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->references('id')
                ->on('estudiantes')->onDelete('cascade');
            $table->date('fecha_estipulada');
            $table->boolean('status');
            $table->date('fecha_pago')->nullable();
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
        Schema::dropIfExists('estudiantes_cuotas');
    }
}
