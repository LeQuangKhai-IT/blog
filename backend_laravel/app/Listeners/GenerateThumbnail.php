<?php

namespace App\Listeners;

use App\Events\MediaUploaded;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class GenerateThumbnail
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
            $image = Image::make(Storage::disk('local')->get($event->media->file_path));
            $image->resize(150, 150, function ($constraint) {
                $constraint->aspectRatio();
            });

            $thumbnailPath = 'thumbnails/' . $event->media->file_name;
            Storage::disk('local')->put($thumbnailPath, (string) $image->encode());
        }
    }
}
