<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintsToPaymentsTable extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->unique('inscription_id', 'payments_inscription_unique');
            $table->unique('purchasenumber', 'payments_purchase_number_unique');
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique('payments_inscription_unique');
            $table->dropUnique('payments_purchase_number_unique');
        });
    }
}
