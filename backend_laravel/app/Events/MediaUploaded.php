<?php

namespace App\Events;

use App\Models\Media;
use App\Models\User;
use Illuminate\Queue\SerializesModels;

class MediaUploaded
{
    use SerializesModels;

    public $media;
    public $user;

    /**
     * Create a new event instance.
     *
     * @param \App\Models\Media $media
     * @param \App\Models\User $user
     */
    public function __construct(Media $media, User $user)
    {
        $this->media = $media;
        $this->user = $user;
    }
}
