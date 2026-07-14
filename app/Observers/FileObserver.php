<?php

namespace App\Observers;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileObserver
{
    /**
     * Handle the File "created" event.
     *
     * @return void
     */
    public function created(File $file) {
        //
    }

    /**
     * Handle the File "updated" event.
     *
     * @return void
     */
    public function updated(File $file) {
        //
    }

    /**
     * Handle the File "deleted" event.
     *
     * @return void
     */
    public function deleted(File $file) {
        if (Storage::exists($file->url)) {
            Storage::delete($file->url);
        }
    }

    /**
     * Handle the File "restored" event.
     *
     * @return void
     */
    public function restored(File $file) {
        //
    }

    /**
     * Handle the File "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(File $file) {
        //
    }
}
