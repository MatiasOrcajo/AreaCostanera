<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDescuentosCantidadegresadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descuentos_cantidadegresados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('descuento_20_a_30')->nullable()->default(0);
            $table->unsignedBigInteger('descuento_31_a_50')->nullable()->default(0);
            $table->unsignedBigInteger('descuento_51_a_70')->nullable()->default(0);
            $table->unsignedBigInteger('descuento_71_a_100')->nullable()->default(0);
            $table->unsignedBigInteger('descuento_101_a_150')->nullable()->default(0);
            $table->unsignedBigInteger('descuento_151_o_mas')->nullable()->default(0);
            $table->timestamps();
        });

        $descuento = new \App\Models\DescuentosCantidadegresados();
        $descuento->descuento_20_a_30 = 0;
        $descuento->descuento_31_a_50 = 0;
        $descuento->descuento_51_a_70 = 0;
        $descuento->descuento_71_a_100 = 0;
        $descuento->descuento_101_a_150 = 0;
        $descuento->descuento_151_o_mas = 0;

        $descuento->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('descuentos_cantidadegresados');
    }
}
