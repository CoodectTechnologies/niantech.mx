<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Image;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $posts = BlogPost::factory(4)->create();
        foreach ($posts as $post) {
            // Image::factory(1)->create([
            //     'imageable_id' => $post->id,
            //     'imageable_type' => BlogPost::class,
            // ]);
        }
        BlogPost::regenerateCache();
    }
}
