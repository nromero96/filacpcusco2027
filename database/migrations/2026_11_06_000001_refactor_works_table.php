<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RefactorWorksTable extends Migration
{
    public function up()
    {
        $newColumns = [
            'first_name' => fn (Blueprint $table) => $table->string('first_name')->nullable(),
            'last_name' => fn (Blueprint $table) => $table->string('last_name')->nullable(),
            'email' => fn (Blueprint $table) => $table->string('email')->nullable(),
            'whatsapp_phone' => fn (Blueprint $table) => $table->string('whatsapp_phone', 30)->nullable(),
            'current_position' => fn (Blueprint $table) => $table->string('current_position')->nullable(),
            'institution' => fn (Blueprint $table) => $table->string('institution')->nullable(),
            'city' => fn (Blueprint $table) => $table->string('city', 120)->nullable(),
            'country' => fn (Blueprint $table) => $table->string('country', 120)->nullable(),
            'title' => fn (Blueprint $table) => $table->string('title')->nullable(),
            'area' => fn (Blueprint $table) => $table->string('area', 80)->nullable(),
            'area_other' => fn (Blueprint $table) => $table->string('area_other')->nullable(),
            'abstract' => fn (Blueprint $table) => $table->text('abstract')->nullable(),
        ];

        foreach ($newColumns as $column => $definition) {
            if (!Schema::hasColumn('works', $column)) {
                Schema::table('works', $definition);
            }
        }

        DB::table('works')
            ->join('users', 'users.id', '=', 'works.user_id')
            ->select('works.id', 'users.name', 'users.lastname', 'users.second_lastname', 'users.email', 'users.phone_code', 'users.phone_number', 'users.workplace', 'users.city', 'users.country')
            ->orderBy('works.id')
            ->each(function ($work) {
                DB::table('works')->where('id', $work->id)->update([
                    'first_name' => mb_strtoupper(trim((string) $work->name), 'UTF-8'),
                    'last_name' => mb_strtoupper(trim(implode(' ', array_filter([$work->lastname, $work->second_lastname]))), 'UTF-8'),
                    'email' => mb_strtolower((string) $work->email, 'UTF-8'),
                    'whatsapp_phone' => trim((string) $work->phone_code.' '.(string) $work->phone_number),
                    'current_position' => $work->workplace,
                    'institution' => $work->workplace,
                    'city' => $work->city,
                    'country' => $work->country,
                ]);
            });

        if (Schema::hasColumn('works', 'knowledge_area')) {
            DB::table('works')->whereNull('area')->update(['area' => DB::raw('knowledge_area')]);
        }
        if (Schema::hasColumn('works', 'description')) {
            DB::table('works')->whereNull('abstract')->update(['abstract' => DB::raw('description')]);
        }

        $obsoleteColumns = [
            'knowledge_area', 'category', 'author_coauthors', 'description', 'references',
            'file_1', 'file_2', 'file_3', 'file_4', 'file_5', 'file_6',
            'comment', 'user_id_calificador', 'qualification',
            'poster_file', 'poster_date_uploaded', 'poster_verification_status', 'poster_count_views',
        ];

        foreach ($obsoleteColumns as $column) {
            if (Schema::hasColumn('works', $column)) {
                Schema::table('works', fn (Blueprint $table) => $table->dropColumn($column));
            }
        }
    }

    public function down()
    {
        Schema::table('works', function (Blueprint $table) {
            $table->string('knowledge_area')->nullable();
            $table->string('category')->nullable();
            $table->string('author_coauthors')->nullable();
            $table->text('description')->nullable();
            $table->text('references')->nullable();
            for ($number = 1; $number <= 6; $number++) {
                $table->string('file_'.$number)->nullable();
            }
            $table->string('status')->nullable();
            $table->string('comment')->nullable();
            $table->unsignedBigInteger('user_id_calificador')->nullable();
            $table->integer('qualification')->nullable();
            $table->string('poster_file')->nullable();
            $table->dateTime('poster_date_uploaded')->nullable();
            $table->string('poster_verification_status')->nullable();
            $table->integer('poster_count_views')->default(0);
        });

        Schema::table('works', function (Blueprint $table) {
            $table->dropColumn([
                'first_name', 'last_name', 'email', 'whatsapp_phone', 'current_position', 'city',
                'country', 'area', 'area_other', 'abstract',
            ]);
        });
    }
}
