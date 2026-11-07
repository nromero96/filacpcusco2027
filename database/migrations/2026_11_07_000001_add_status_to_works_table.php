<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToWorksTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('works', 'status')) {
            Schema::table('works', function (Blueprint $table) {
                $table->string('status', 30)->default('Enviado')->after('abstract');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('works', 'status')) {
            Schema::table('works', fn (Blueprint $table) => $table->dropColumn('status'));
        }
    }
}
