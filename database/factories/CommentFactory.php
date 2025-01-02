<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Liamtseva\Cinema\Models\Comment;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Selection;
use Liamtseva\Cinema\Models\User;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    public function definition(): array
    {
        // Список доступних класів для `commentable_type`
        $commentableClasses = [
            Movie::class,
            Episode::class,
            Selection::class,
        ];

        // Випадковий вибір класу
        $commentableClass = $this->faker->randomElement($commentableClasses);

        // Створення або вибір випадкового запису відповідного класу
        $commentable = $commentableClass::query()->inRandomOrder()->first()
            ?? $commentableClass::factory()->create();

        return [
            'commentable_id' => $commentable->id,
            'commentable_type' => $commentableClass,
            'user_id' => User::inRandomOrder()->value('id'),
            'is_spoiler' => $this->faker->boolean(10), // 10% ймовірність, що це спойлер
            'body' => $this->faker->paragraph(),
        ];
    }

    /**
     * Встановлює батьківський коментар (для вкладених коментарів).
     */
    public function withParent(Comment $parent): self
    {
        return $this->state(fn () => ['parent_id' => $parent->id]);
    }

    /**
     * Для коментаря, який є кореневим.
     */
    public function root(): self
    {
        return $this->state(fn () => ['parent_id' => null]);
    }

    /**
     * Встановлює коментар як відповідь на інший коментар.
     */
    public function replyTo(Comment $parentComment): self
    {
        return $this->state(fn () => [
            'parent_id' => $parentComment->id,
            'commentable_id' => $parentComment->commentable_id,
            'commentable_type' => $parentComment->commentable_type,
        ]);
    }

    /**
     * Встановлює поліморфний зв'язок з вказаним типом і ID.
     */
    public function forCommentable(Model $commentable): self
    {
        return $this->state(fn () => [
            'commentable_id' => $commentable->id,
            'commentable_type' => get_class($commentable),
        ]);
    }

    /**
     * Встановлює користувача, який залишив коментар.
     */
    public function forUser(User $user): self
    {
        return $this->state(fn () => [
            'user_id' => $user->id,
        ]);
    }
}
