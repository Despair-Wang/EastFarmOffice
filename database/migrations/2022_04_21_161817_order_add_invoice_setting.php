<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderAddInvoiceSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        schema::table('good_orders', function (Blueprint $table) {
            $table->string('invoiceType', 50)->after('freight');
            $table->string('taxNumber', 50)->nullable()->after('invoiceType');
            $table->string('invoiceSendType', 50)->nullable()->after('taxNumber');
            $table->integer('invoiceZipcode')->nullable()->after('invoiceSendType');
            $table->string('invoiceAddress', 50)->nullable()->after('invoiceZipcode');
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
            $table->dropColumn('invoiceType');
            $table->dropColumn('taxNumber');
            $table->dropColumn('invoiceSendType');
            $table->dropColumn('invoiceZipcode');
            $table->dropColumn('invoiceAddress');
        });
    }
}