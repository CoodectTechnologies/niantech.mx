<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @return void
     */
    public function created(Order $order) {
        //
    }

    /**
     * Handle the Order "updated" event.
     *
     * @return void
     */
    public function updated(Order $order) {
        //
    }

    /**
     * Handle the Order "deleted" event.
     *
     * @return void
     */
    public function deleted(Order $order) {
        //
    }

    /**
     * Handle the Order "restored" event.
     *
     * @return void
     */
    public function restored(Order $order) {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @return void
     */
    public function forceDeleted(Order $order) {
        //
    }
}
