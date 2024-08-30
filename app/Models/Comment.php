<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Author;
use WendellAdriel\Lift\Lift;
use WendellAdriel\Lift\Attributes\DB;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use WendellAdriel\Lift\Attributes\Relations\BelongsTo;

#[DB(timestamps: true)]
#[BelongsTo(Author::class)]
#[BelongsTo(Book::class)]
class Comment extends Model
{
    use HasFactory;
    use Lift;

    #[PrimaryKey]
    public int $id;

    public string $content;
}
