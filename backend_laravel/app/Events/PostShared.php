<?php

namespace App\Events;


use Illuminate\Queue\SerializesModels;

class PostShared
{
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct()
    {
        //
    }
}
