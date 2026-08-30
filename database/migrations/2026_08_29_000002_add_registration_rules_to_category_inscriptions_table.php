<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddRegistrationRulesToCategoryInscriptionsTable extends Migration
{
    public function up()
    {
        Schema::table('category_inscriptions', function (Blueprint $table) {
            $table->boolean('requires_document')->default(false);
            $table->boolean('requires_voucher')->default(false);
            $table->boolean('uses_special_code')->default(false);
            $table->boolean('shows_payment')->default(false);
            $table->boolean('waives_accompanist_fee')->default(false);
        });

        DB::table('category_inscriptions')->where('id', 3)->update(['requires_document' => true]);
        DB::table('category_inscriptions')->whereIn('id', [1, 2, 3])->update(['requires_voucher' => true]);
        DB::table('category_inscriptions')->where('id', 5)->update(['uses_special_code' => true]);
        DB::table('category_inscriptions')->whereIn('id', [1, 2, 3, 4, 5])->update(['shows_payment' => true]);
        DB::table('category_inscriptions')->whereIn('id', [9, 11])->update(['waives_accompanist_fee' => true]);
    }

    public function down()
    {
        Schema::table('category_inscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'requires_document',
                'requires_voucher',
                'uses_special_code',
                'shows_payment',
                'waives_accompanist_fee',
            ]);
        });
    }
}
