<?php

namespace App\Models;

use App\Models\Book;
use Carbon\CarbonImmutable;
use WendellAdriel\Lift\Lift;
use WendellAdriel\Lift\Attributes\DB;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\Relations\BelongsToMany;

#[DB(timestamps: true)]
#[BelongsToMany(Book::class)]
class Author extends Model
{
    use HasFactory;
    use Lift;

    #[PrimaryKey]
    public int $id;

    #[Fillable]
    public string $name;

    #[Fillable]
    public string $biography;

    #[Cast('immutable_date')]
    #[Fillable]
    public CarbonImmutable $birthdate;

    #[Fillable]
    public string $nationality;
}
