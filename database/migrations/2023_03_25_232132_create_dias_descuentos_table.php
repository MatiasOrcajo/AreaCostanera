<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiasDescuentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dias_descuentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dia_id')->references('id')
                ->on('dias')->onDelete('cascade');
            $table->unsignedBigInteger('descuento_20_a_30')->nullable()->default(0);
            $table->unsignedBigInteger('descuento_31_a_50')->nullable()->default(0);
            $table->unsignedBigInteger('descuento_51_a_70')->nullable()->default(0);
            $table->unsignedBigInteger('descuento_71_a_100')->nullable()->default(0);
            $table->unsignedBigInteger('descuento_101_a_150')->nullable()->default(0);
            $table->unsignedBigInteger('descuento_151_o_mas')->nullable()->default(0);
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
        Schema::dropIfExists('diasDescuentos');
    }
}
