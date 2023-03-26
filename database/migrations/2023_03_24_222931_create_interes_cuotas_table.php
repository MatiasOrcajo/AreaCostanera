<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInteresCuotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interes_cuotas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('interes');
            $table->timestamps();
        });

        $interes = new \App\Models\InteresCuota();
        $interes->interes = 5;
        $interes->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('interes_cuotas');
    }
}
