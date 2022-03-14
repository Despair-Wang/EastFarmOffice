<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagForPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_for_posts', function (Blueprint $table) {
            $table->id();
            $table->integer('postId'); //標籤所屬的文章ID
            $table->integer('tagId'); //該標籤的資訊所在ID
            $table->integer('state')->default(2); //標籤所屬的文章狀態,0為封存、1為開放、2為未完成
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
        Schema::dropIfExists('tag_for_posts');
    }
}