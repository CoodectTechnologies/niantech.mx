<?php

namespace App\Traits;

trait HasPlans
{
    abstract public function subscriptionActive();
    public function hasPermissionViaPlan($permission) {
        if ($subscription = $this->subscriptionActive) {
            return $subscription->plan->hasPermissionTo($permission);
        }

        return false;
    }
    public function getPermissionsViaPlans() {
        if ($subscription = $this->subscriptionActive) {
            return $subscription->plan->getAllPermissions();
        }

        return collect();
    }
}
