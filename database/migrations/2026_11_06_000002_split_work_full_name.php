<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SplitWorkFullName extends Migration
{
    public function up()
    {
        Schema::table('works', function (Blueprint $table) {
            if (!Schema::hasColumn('works', 'first_name')) {
                $table->string('first_name')->nullable();
            }
            if (!Schema::hasColumn('works', 'last_name')) {
                $table->string('last_name')->nullable();
            }
        });

        DB::table('works')
            ->join('users', 'users.id', '=', 'works.user_id')
            ->select('works.id', 'users.name', 'users.lastname', 'users.second_lastname')
            ->orderBy('works.id')
            ->each(function ($work) {
                DB::table('works')->where('id', $work->id)->update([
                    'first_name' => mb_strtoupper(trim((string) $work->name), 'UTF-8'),
                    'last_name' => mb_strtoupper(trim(implode(' ', array_filter([$work->lastname, $work->second_lastname]))), 'UTF-8'),
                ]);
            });

        if (Schema::hasColumn('works', 'full_name')) {
            Schema::table('works', fn (Blueprint $table) => $table->dropColumn('full_name'));
        }
    }

    public function down()
    {
        if (!Schema::hasColumn('works', 'full_name')) {
            Schema::table('works', fn (Blueprint $table) => $table->string('full_name')->nullable());
        }

        DB::table('works')->update([
            'full_name' => DB::raw("TRIM(CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, '')))"),
        ]);

        Schema::table('works', fn (Blueprint $table) => $table->dropColumn(['first_name', 'last_name']));
    }
}
