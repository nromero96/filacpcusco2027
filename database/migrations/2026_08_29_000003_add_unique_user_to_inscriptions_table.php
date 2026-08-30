<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddUniqueUserToInscriptionsTable extends Migration
{
    public function up()
    {
        $duplicatedUsers = DB::table('inscriptions')
            ->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($duplicatedUsers) {
            throw new \RuntimeException('No se puede crear el índice único: existen usuarios con más de una inscripción.');
        }

        Schema::table('inscriptions', function (Blueprint $table) {
            $table->unique('user_id', 'inscriptions_user_id_unique');
        });
    }

    public function down()
    {
        Schema::table('inscriptions', function (Blueprint $table) {
            $table->dropUnique('inscriptions_user_id_unique');
        });
    }
}
