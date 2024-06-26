<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('fecha');
            $table->unsignedInteger('diners');
            $table->unsignedFloat('discount')->nullable()->default(0.0);
            $table->unsignedBigInteger('total');
            $table->boolean('status')->nullable()->default(1);
            $table->foreignIdFor(\App\Models\Menu::class, 'menu_id');
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
        Schema::dropIfExists('social_events');
    }
}
