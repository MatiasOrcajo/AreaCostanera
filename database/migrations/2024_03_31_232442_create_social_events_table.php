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
            $table->text('slug');
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
