<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateToursTable extends Migration {
    public function up() { Schema::create('tours', function (Blueprint $table) { $table->id(); $table->string('name'); $table->text('description')->nullable(); $table->date('tour_date')->nullable(); $table->time('start_time')->nullable(); $table->time('end_time')->nullable(); $table->string('meeting_point')->nullable(); $table->decimal('price',10,2); $table->decimal('accompanist_price',10,2)->default(0); $table->unsignedInteger('capacity')->nullable(); $table->string('status',20)->default('active'); $table->timestamps(); }); }
    public function down() { Schema::dropIfExists('tours'); }
}
