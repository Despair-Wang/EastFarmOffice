<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderAddReceiptSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::table('good_orders', function (Blueprint $table) {
            $table->string('receiptType', 50)->after('freight');
            $table->string('taxNumber', 50)->nullable()->after('receiptType');
            $table->string('receiptSendType', 50)->nullable()->after('taxNumber');
            $table->integer('receiptZipcode', 50)->nullable()->after('receiptSendType');
            $table->string('receiptAddress', 50)->nullable()->after('receiptZipcode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}