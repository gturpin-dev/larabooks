<?php

namespace App\Models;

use App\Models\Author;
use App\Models\Comment;
use App\Casts\MoneyCast;
use WendellAdriel\Lift\Lift;
use ApiPlatform\Metadata\Get;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use ApiPlatform\Metadata\ApiResource;
use WendellAdriel\Lift\Attributes\DB;
use ApiPlatform\Metadata\GetCollection;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\Cast;
use ApiPlatform\Metadata\QueryParameter;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use ApiPlatform\Laravel\Eloquent\Filter\OrderFilter;
use WendellAdriel\Lift\Attributes\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use WendellAdriel\Lift\Attributes\Relations\BelongsToMany;
use ApiPlatform\Laravel\Eloquent\Filter\PartialSearchFilter;

#[DB(timestamps: true)]
#[HasMany(Comment::class)]
#[BelongsToMany(Author::class)]
#[ApiResource(
    middleware            : 'auth:sanctum',
    paginationEnabled     : true,
    paginationItemsPerPage: 10,
    operations            : [
        new GetCollection(),
        new Get(),
    ]
)]
#[QueryParameter(
    key   : ':property',
    filter: PartialSearchFilter::class,
)]
#[QueryParameter(
    key   : 'sort[:property]',
    filter: OrderFilter::class
)]
class Book extends Model
{
    use HasFactory;
    use Lift;
    use HasSlug;

    #[PrimaryKey]
    public int $id;

    #[Fillable]
    public string $title;

    /**
     * International Standard Book Number
     */
    #[Fillable]
    public string $isbn;

    #[Fillable]
    public string $genre;

    #[Fillable]
    #[Cast(MoneyCast::class)]
    public float $price;

    #[Fillable]
    public string $description;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->usingSeparator('-');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
