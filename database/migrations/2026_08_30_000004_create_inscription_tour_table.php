<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateInscriptionTourTable extends Migration {
    public function up() { Schema::create('inscription_tour', function (Blueprint $table) { $table->id(); $table->foreignId('tour_id')->constrained()->cascadeOnDelete(); $table->foreignId('inscription_id')->constrained()->cascadeOnDelete(); $table->decimal('unit_price',10,2); $table->boolean('has_accompanist')->default(false); $table->decimal('accompanist_price',10,2)->default(0); $table->string('accompanist_name')->nullable(); $table->string('accompanist_document_type',40)->nullable(); $table->string('accompanist_document_number',30)->nullable(); $table->string('accompanist_phone',30)->nullable(); $table->timestamps(); $table->unique(['tour_id','inscription_id']); }); }
    public function down() { Schema::dropIfExists('inscription_tour'); }
}
