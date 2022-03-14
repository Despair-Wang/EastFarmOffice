<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('good_stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('goodId');
            $table->integer('goodType')->default(1);
            $table->integer('export');
            $table->integer('import');
            $table->integer('stock');
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
        Schema::dropIfExists('good_stocks');
    }
}