<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\Category::factory(5)->create();
        \App\Models\Tag::factory(6)->create();
        \App\Models\Setting::factory(1)->create();
        \App\Models\User::factory(10)->create();
        \App\Models\Post::factory(30)->create();
        \App\Models\Comment::factory(50)->create();
        \App\Models\Like::factory(346)->create();
    }
}
