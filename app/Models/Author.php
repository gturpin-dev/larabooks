<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Comment;
use Carbon\CarbonImmutable;
use WendellAdriel\Lift\Lift;
use WendellAdriel\Lift\Attributes\DB;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[DB(timestamps: true)]
#[HasMany(Book::class)]
#[HasMany(Comment::class)]
class Author extends Model
{
    use HasFactory;
    use Lift;

    #[PrimaryKey]
    public int $id;

    public string $name;

    public string $biography;

    public CarbonImmutable $birthdate;

    public string $nationality;
}
