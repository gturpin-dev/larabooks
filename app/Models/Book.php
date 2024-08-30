<?php

namespace App\Models;

use App\Models\Author;
use App\Models\Comment;
use WendellAdriel\Lift\Lift;
use WendellAdriel\Lift\Attributes\DB;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[DB(timestamps: true)]
#[HasMany(Author::class)]
#[HasMany(Comment::class)]
class Book extends Model
{
    use HasFactory;
    use Lift;

    #[PrimaryKey]
    public int $id;

    public string $title;

    /**
     * International Standard Book Number
     */
    public string $isbn;

    public string $genre;

    public float $price;

    public string $description;
}
