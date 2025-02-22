<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Queue\SerializesModels;

class PostUpdated
{
    use SerializesModels;

    public $post;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Post $post The post that was updated.
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }
}
