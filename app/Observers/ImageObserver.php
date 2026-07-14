<?php

namespace App\Observers;

use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageObserver
{
    /**
     * Handle the Image "created" event.
     *
     * @return void
     */
    public function created(Image $image) {
        //
    }

    /**
     * Handle the Image "updated" event.
     *
     * @return void
     */
    public function updated(Image $image) {
        //
    }

    /**
     * Handle the Image "deleted" event.
     *
     * @return void
     */
    public function deleted(Image $image) {
        if (Storage::exists($image->url)) {
            Storage::delete($image->url);
        }
    }

    /**
     * Handle the Image "restored" event.
     *
     * @return void
     */
    public function restored(Image $image) {
        //
    }

    /**
     * Handle the Image "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Image $image) {
        //
    }
}
