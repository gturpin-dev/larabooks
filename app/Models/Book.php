<?php

namespace App\Models;

use ApiPlatform\Laravel\Eloquent\Filter\OrderFilter;
use ApiPlatform\Laravel\Eloquent\Filter\PartialSearchFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\QueryParameter;
use App\Casts\MoneyCast;
use App\Http\Requests\CreateBookRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use WendellAdriel\Lift\Attributes\Cast;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Attributes\Fillable;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Relations\BelongsToMany;
use WendellAdriel\Lift\Attributes\Relations\HasMany;
use WendellAdriel\Lift\Lift;

#[DB( timestamps: true )]
#[HasMany( Comment::class )]
#[BelongsToMany( Author::class )]
#[ApiResource(
    middleware            : 'auth:sanctum',
    rules                 : CreateBookRequest::class,
    paginationEnabled     : true,
    paginationItemsPerPage: 10,
    operations            : [
        new GetCollection,
        new Get,
        new Post,
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
    use HasSlug;
    use Lift;

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
    #[Cast( MoneyCast::class )]
    public float $price;

    #[Fillable]
    public string $description;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom( 'title' )
            ->saveSlugsTo( 'slug' )
            ->usingSeparator( '-' );
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
