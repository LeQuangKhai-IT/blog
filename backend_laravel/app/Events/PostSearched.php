<?php

namespace App\Events;


use Illuminate\Queue\SerializesModels;

class PostSearched
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
