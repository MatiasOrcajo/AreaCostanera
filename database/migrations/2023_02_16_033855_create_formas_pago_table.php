<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormasPagoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formas_pago', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('interes');
            $table->timestamps();
        });

        $formaPago = new \App\Models\FormasPago();
        $formaPago->nombre = 'Efectivo';
        $formaPago->interes = 0;
        $formaPago->save();

        $formaPago = new \App\Models\FormasPago();
        $formaPago->nombre = '3 cuotas';
        $formaPago->interes = 30;
        $formaPago->save();

        $formaPago = new \App\Models\FormasPago();
        $formaPago->nombre = '6 cuotas';
        $formaPago->interes = 50;
        $formaPago->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formas_pago');
    }
}
