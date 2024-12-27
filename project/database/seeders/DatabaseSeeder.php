<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Tag::factory(150)->create();
        Post::factory(5000)->create()->each(function (Post $post) {
            $post->tags()->attach(Tag::query()->inRandomOrder()->limit(4)->get());
            $post->likes()->attach(User::query()->inRandomOrder()->limit(3)->get());
        });
    }
}
