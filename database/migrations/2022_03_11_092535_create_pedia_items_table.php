<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePediaItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedia_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('category');
            $table->string('image')->default('/assets/pedia/noImage.png');
            $table->integer('version')->default(0);
            $table->integer('fatherId')->nullable();
            $table->boolean('state')->default(1);
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
        Schema::dropIfExists('pedia_items');
    }
}