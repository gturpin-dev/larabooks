<?php

namespace App\Models;

use App\Models\Book;
use Carbon\CarbonImmutable;
use WendellAdriel\Lift\Lift;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\ApiResource;
use WendellAdriel\Lift\Attributes\DB;
use ApiPlatform\Metadata\GetCollection;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use WendellAdriel\Lift\Attributes\Relations\BelongsToMany;

#[DB(timestamps: true)]
#[BelongsToMany(Book::class)]
#[ApiResource(
    paginationEnabled     : true,
    paginationItemsPerPage: 10,
    operations            : [
        new GetCollection(),
        new Get(),
    ]
)]
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
