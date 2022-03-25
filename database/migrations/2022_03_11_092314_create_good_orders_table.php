<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_orders', function (Blueprint $table) {
            $table->id();
            $table->string('serial');
            $table->integer('userId');
            $table->string('name');
            $table->string('tel');
            $table->integer('zipcode');
            $table->string('address');
            $table->integer('total');
            $table->string('pay');
            $table->integer('freight');
            $table->string('remark')->nullable();
            $table->string('state')->default(2); //0-取消，1-成立，2-未完成，3-已結單
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('good_orders');
    }
}