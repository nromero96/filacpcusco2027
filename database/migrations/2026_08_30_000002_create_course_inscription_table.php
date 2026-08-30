<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseInscriptionTable extends Migration
{
    public function up()
    {
        Schema::create('course_inscription', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('inscription_id')->constrained()->cascadeOnDelete();
            $table->decimal('unit_price', 10, 2);
            $table->timestamps();
            $table->unique(['course_id', 'inscription_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_inscription');
    }
}
