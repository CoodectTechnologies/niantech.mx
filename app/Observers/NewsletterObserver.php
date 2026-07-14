<?php

namespace App\Observers;

use App\Models\Newsletter as NewsletterModel;
use Spatie\Newsletter\Facades\Newsletter;

class NewsletterObserver
{
    /**
     * Handle the Newsletter "created" event.
     *
     * @param  NewsletterModel  $newsletter
     * @return void
     */
    public function created(NewsletterModel $newsletterModel) {
        if ($this->getStatus()) {
            if (! Newsletter::isSubscribed($newsletterModel->email)) {
                Newsletter::subscribe($newsletterModel->email);
            }
        }
    }

    /**
     * Handle the Newsletter "updated" event.
     *
     * @param  NewsletterModel  $newsletter
     * @return void
     */
    public function updated(NewsletterModel $newsletterModel) {
        //
    }

    /**
     * Handle the Newsletter "deleted" event.
     *
     * @param  NewsletterModel  $newsletter
     * @return void
     */
    public function deleted(NewsletterModel $newsletterModel) {
        if ($this->getStatus()) {
            if (Newsletter::isSubscribed($newsletterModel->email)) {
                Newsletter::unsubscribe($newsletterModel->email);
            }
        }
    }

    /**
     * Handle the Newsletter "restored" event.
     *
     * @return void
     */
    public function restored(NewsletterModel $newsletter) {
        //
    }

    /**
     * Handle the Newsletter "force deleted" event.
     *
     * @param  NewsletterModel  $newsletter
     * @return void
     */
    public function forceDeleted(NewsletterModel $newsletterModel) {
        //
    }

    private function getStatus() {
        $response = false;
        if (
            config('newsletter.status') &&
            config('newsletter.driver_arguments.api_key') &&
            config('newsletter.lists.subscribers.id')
        ) {
            $response = true;
        }

        return $response;
    }
}
