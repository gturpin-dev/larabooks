<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use WendellAdriel\Lift\Attributes\DB;
use WendellAdriel\Lift\Attributes\PrimaryKey;
use WendellAdriel\Lift\Attributes\Relations\BelongsTo;
use WendellAdriel\Lift\Lift;

#[DB( timestamps: true )]
#[BelongsTo( User::class )]
#[BelongsTo( Book::class )]
class Comment extends Model
{
    use HasFactory;
    use Lift;

    #[PrimaryKey]
    public int $id;
    public string $content;
}
