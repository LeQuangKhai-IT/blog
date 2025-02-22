<?php

namespace App\Events;

use App\Models\Post;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class PostLiked
{
    use SerializesModels;

    public $post;
    public $user;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\User $user The user that was liked.
     * @param \App\Models\Post $post The post that was liked.
     * @return void
     */
    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }
}
