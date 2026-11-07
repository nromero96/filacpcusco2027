<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('whatsapp_phone', 30);
            $table->string('current_position');
            $table->string('institution');
            $table->string('city', 120);
            $table->string('country', 120);
            $table->string('title');
            $table->string('area', 80);
            $table->string('area_other')->nullable();
            $table->text('abstract');
            $table->string('status', 30)->default('Enviado');
            $table->timestamps();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('works');
    }
}
