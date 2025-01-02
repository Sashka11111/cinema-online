<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Liamtseva\Cinema\Models\Comment;
use Liamtseva\Cinema\Models\CommentLike;
use Liamtseva\Cinema\Models\User;

/**
 * @extends Factory<CommentLike>
 */
class CommentLikeFactory extends Factory
{
    public function definition(): array
    {
        $comment = Comment::inRandomOrder()->first();

        return [
            'comment_id' => $comment->id,
            'user_id' => User::factory(),
            'is_liked' => $this->faker->boolean(),
        ];
    }

    public function liked(): self
    {
        return $this->state(fn () => ['is_liked' => true]);
    }

    public function disliked(): self
    {
        return $this->state(fn () => ['is_liked' => false]);
    }
}
