<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Author;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Book::factory()
            ->count(30)
            ->create()
            ->each( function (Book $book) {
                $book->authors()->attach(
                    Author::factory()->count(rand(1, 3))->create()
                );

                $book->comments()->createMany(
                    Comment::factory()->count(rand(0, 5))->make()->toArray()
                );
            } );
    }
}
