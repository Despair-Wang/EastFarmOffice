<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\GoodCategory;
use App\Models\PediaCategory;
use App\Models\PediaTag;
use App\Models\Photo;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostTag;
use App\Models\Tag_for_post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('post_tags')->truncate();
        DB::table('post_categories')->truncate();
        DB::table('posts')->truncate();
        DB::table('tag_for_posts')->truncate();
        DB::table('albums')->truncate();
        DB::table('photos')->truncate();
        DB::table('pedia_tags')->truncate();
        DB::table('pedia_categories')->truncate();
        DB::table('good_categories')->truncate();
        // \App\Models\User::factory(10)->create();
        PostTag::factory(16)->create();
        PostCategory::factory(10)->create();
        Post::factory(50)->create();
        Tag_for_post::factory(150)->create();
        Album::factory(20)->create();
        Photo::factory(300)->create();
        PediaTag::factory(16)->create();
        PediaCategory::factory(10)->create();
        GoodCategory::factory(10)->create();
    }
}