<?php

namespace App\Observers;

use App\Exceptions\OdooException;
use App\Models\User;
use App\Services\Integrations\Odoo\Customer\CustomerService;
use Illuminate\Support\Facades\Storage;

class UserObserver
{
    public function creating(User $user) {
        $this->saveOdoo($user);
    }
    public function created(User $user) {
        //
    }
    public function updating(User $user) {
        $this->saveOdoo($user);
    }
    public function updated(User $user) {
        if ($user->hasStripeId()) {
            $user->syncStripeCustomerDetails();
        }
    }
    public function deleted(User $user) {
        if ($user->image && Storage::exists($user->image->url)) {
            Storage::delete($user->image->url);
        }
    }
    public function restored(User $user) {
        //
    }
    public function forceDeleted(User $user) {
        //
    }
    private function saveOdoo(User $user): void {
        if (config('services.odoo.status')) {
            $customerService = new CustomerService;
            $customer = $customerService->save($user);
            if (isset($customer['provider_id']) && $customer['provider_id']) {
                $user->provider = $customer['provider'];
                $user->provider_id = $customer['provider_id'];
            } else {
                throw new OdooException(__('We were unable to complete your registration at this time. Please try again.'));
            }
        }
    }
}
