<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderAddPayInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('good_orders', function (Blueprint $table) {
            $table->string('payName', 50)->nullable()->after('remark');
            $table->string('payTime', 50)->default('not_yet')->after('payName');
            $table->string('payAccount', 5)->default('00000')->after('payTime');
            $table->integer('payAmount')->default('0')->after('payAccount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('good_orders', function (Blueprint $table) {
            $table->dropColumn('payName');
            $table->dropColumn('payTime');
            $table->dropColumn('payAccount');
            $table->dropColumn('payAmount');
        });
    }
}