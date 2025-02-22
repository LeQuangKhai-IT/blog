<?php

namespace App\Listeners;

use App\Events\MediaUploaded;
use Illuminate\Support\Facades\Storage;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class OptimizeMedia
{
    /**
     * Handle the event.
     *
     * @param \App\Events\MediaUploaded $event
     * @return void
     */
    public function handle(MediaUploaded $event)
    {
        if ($event->media->file_type === 'image') {
            $optimizerChain = OptimizerChainFactory::create();
            $filePath = Storage::disk('local')->path($event->media->file_path);

            $optimizerChain->optimize($filePath);
        }
    }
}
