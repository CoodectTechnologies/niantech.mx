<?php

namespace App\Observers;

use App\Exceptions\OdooException;
use App\Models\Address;
use App\Services\Integrations\Odoo\Address\AddressService;

class AddressObserver
{
    public function creating(Address $address) {
        $this->saveOdoo($address);
    }
    public function created(Address $address): void {
        $this->removeDefaultOthers($address);
    }
    public function updating(Address $address) {
        $this->saveOdoo($address);
    }
    public function updated(Address $address): void {
        $this->removeDefaultOthers($address);
    }
    public function deleted(Address $address): void {
        $this->deleteOdoo($address);
    }
    public function restored(Address $address): void {
        //
    }
    public function forceDeleted(Address $address): void {
        $this->deleteOdoo($address);
    }
    private function saveOdoo(Address $address): void {
        if (config('services.odoo.status')) {
            $address->load(['state.country', 'user']);
            $customerService = new AddressService;
            $customer = $customerService->save($address);
            if ($address->is_billing) {
                $address->is_billing_default = true;  // Como en odoo solo se puede tener una dirección de facturación, si esta es marcada como de facturación, se asigna como predeterminada de facturación
            }
            if (isset($customer['provider_id']) && $customer['provider_id']) {
                $address->provider = $customer['provider'];
                $address->provider_id = $customer['provider_id'];
            } else {
                throw new OdooException(__('We were unable to complete your registration at this time. Please try again.'));
            }
        }
    }
    private function deleteOdoo(Address $address): void {
        if (config('services.odoo.status') && $address->provider_id) {
            $addressService = new AddressService;
            $result = $addressService->delete((int) $address->provider_id);
            if (! $result) {
                throw new OdooException(__('We were unable to delete your registration at this time. Please try again.'));
            }
        }
    }
    private function removeDefaultOthers(Address $address) {
        $updateData = [];
        if ($address->is_default) {
            $updateData['is_default'] = false;
        }
        if ($address->is_billing_default) {
            $updateData['is_billing_default'] = false;
        }
        if (config('services.odoo.status')) {
            if ($address->is_billing) {
                $updateData['is_billing'] = false;
            }
        }
        if ($updateData && $address->user_id) {
            Address::query()
                ->where('user_id', $address->user_id)
                ->where('id', '<>', $address->id)
                ->update($updateData);
        }
    }
}
