<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediosPagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medios_pago', function (Blueprint $table) {
            $table->id();
            $table->string('metodo');
            $table->integer('iva');
            $table->timestamps();
        });

        $method = new \App\Models\MediosPago();
        $method->metodo = 'Medios electrónicos';
        $method->iva = 21;
        $method->save();

        $method = new \App\Models\MediosPago();
        $method->metodo = 'Efectivo';
        $method->iva = 0;
        $method->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metodo_pago');
    }
}
