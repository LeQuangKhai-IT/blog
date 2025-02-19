<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Like extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'post_id',
        'comment_id',
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
     * Get the like for the a user.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the like for the a post.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function posts(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the like for the a comment.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo|null
     */
    public function comments(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    /**
     * Ensure that either post_id or comment_id is set, but not both.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->post_id && $model->comment_id) {
                throw new \Exception('A like can only belong to either a post or a comment, not both.');
            }

            if (! $model->post_id && ! $model->comment_id) {
                throw new \Exception('A like must belong to either a post or a comment.');
            }
        });
    }
}
