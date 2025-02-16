<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Like>
 */
class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $isPostLike = fake()->boolean();

        return [
            'user_id' => User::pluck('id')->random(),
            'post_id' => $isPostLike ? Post::pluck('id')->random() : null,
            'comment_id' => ! $isPostLike ? Comment::pluck('id')->random() : null,
        ];
    }
}
