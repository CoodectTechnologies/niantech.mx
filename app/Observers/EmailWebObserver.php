<?php

namespace App\Observers;

use App\Models\EmailWeb;
use App\Models\Newsletter;

class EmailWebObserver
{
    /**
     * Handle the EmailWeb "created" event.
     *
     * @return void
     */
    public function created(EmailWeb $emailWeb) {
        // Registrar en el newsletters
        if (! Newsletter::where('email', $emailWeb->email)->first()) {
            Newsletter::create(['email' => $emailWeb->email]);
        }
    }

    /**
     * Handle the EmailWeb "updated" event.
     *
     * @return void
     */
    public function updated(EmailWeb $emailWeb) {
        //
    }

    /**
     * Handle the EmailWeb "deleted" event.
     *
     * @return void
     */
    public function deleted(EmailWeb $emailWeb) {
        //
    }

    /**
     * Handle the EmailWeb "restored" event.
     *
     * @return void
     */
    public function restored(EmailWeb $emailWeb) {
        //
    }

    /**
     * Handle the EmailWeb "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(EmailWeb $emailWeb) {
        //
    }
}
