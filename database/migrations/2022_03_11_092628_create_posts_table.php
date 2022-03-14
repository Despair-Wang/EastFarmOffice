<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image')->default('/assets/post/default.jpg');
            $table->text('content');
            $table->integer('category');
            $table->integer('creator');
            $table->integer('fatherId')->nullable();
            $table->integer('version')->default(0);
            $table->integer('state')->default(2); //0為封存、1為公開、2為未完成
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
        Schema::dropIfExists('posts');
    }
}