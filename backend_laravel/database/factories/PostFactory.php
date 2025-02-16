<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence();

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => fake()->paragraphs(5, true),
            'status' => fake()->randomElement(['draft', 'pending', 'scheduled', 'archived', 'private', 'trash']), // thêm 'published',
            'user_id' => User::pluck('id')->random(),
            'category_id' => Category::pluck('id')->random(),
            'created_at' => fake()->dateTimeBetween('-1 years', 'now'),
            'updated_at' => now(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Post $post) {
            $post->tags()->sync(Tag::pluck('id')->random(rand(2, 4))->toArray());
        });
    }
}
