<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'content',
        'user_id',
        'commentable_id',
        'commentable_type',
        'parent_id',
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
     * Get the parent model (Post, Video, etc.) that the comment belongs to.
     *
     * This defines a polymorphic relationship, allowing the comment
     * to belong to multiple models like Post, Video, etc.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Get the replies (child comments) for this comment.
     *
     * This defines a recursive one-to-many relationship to allow for
     * nested comments. The replies are the comments whose parent_id
     * matches this comment's id.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    /**
     * Get the parent comment of this comment (if any).
     *
     * This defines a recursive relationship where this comment belongs
     * to another comment. If parent_id is null, it means this is a root comment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get the comments for the a post.
     */
    public function posts(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the comments for the a user.
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the like for a comment.
     */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }
}
