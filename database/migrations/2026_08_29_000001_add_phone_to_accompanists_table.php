<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneToAccompanistsTable extends Migration
{
    public function up()
    {
        Schema::table('accompanists', function (Blueprint $table) {
            $table->string('accompanist_phone')->nullable()->after('accompanist_numdocument');
        });
    }

    public function down()
    {
        Schema::table('accompanists', function (Blueprint $table) {
            $table->dropColumn('accompanist_phone');
        });
    }
}
