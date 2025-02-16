<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Analytic extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'id',
        'post_id',
        'views_count',
        'likes_count',
        'shares_count',
        'date',
    ];

    /**
     * The data type of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Get the analytic for the a post.
     */
    public function posts(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
