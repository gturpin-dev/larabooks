<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Author;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->name(),
            'isbn'  => fake()->isbn13(),
            'genre' => fake()->word(),
            'price' => fake()->randomFloat(
                nbMaxDecimals: 2,
                min          : 10,
                max          : 100
            ),
            'description' => fake()->paragraph(),
        ];
    }
}
