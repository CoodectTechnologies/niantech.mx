<?php

namespace App\Observers;

use App\Exceptions\OdooException;
use App\Models\Address;
use App\Integrations\Odoo\Resources\Address\AddressResource;

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
            $addressResource = new AddressResource;
            $customer = $addressResource->save($address);
            if ($address->is_billing) {
                $address->is_billing_default = true;  // Como en odoo solo se puede tener una dirección de facturación, si esta es marcada como de facturación, se asigna como predeterminada de facturación
            }
            if (isset($customer['external_id']) && $customer['external_id']) {
                $address->external = $customer['external'];
                $address->external_id = $customer['external_id'];
            } else {
                throw new OdooException(__('We were unable to complete your registration at this time. Please try again.'));
            }
        }
    }
    private function deleteOdoo(Address $address): void {
        if (config('services.odoo.status') && $address->external_id) {
            $addressResource = new AddressResource;
            $result = $addressResource->delete((int) $address->external_id);
            if (! $result) {
                throw new OdooException(__('We were unable to delete your registration at this time. Please try again.'));
            }
        }
    }
    private function removeDefaultOthers(Address $address) {
        $updateOthers = [];
        if ($address->is_default) {
            $updateOthers['is_default'] = false;
        }
        if ($address->is_billing_default) {
            $updateOthers['is_billing_default'] = false;
        }
        if (config('services.odoo.status')) {
            if ($address->is_billing) {
                $updateOthers['is_billing'] = false;
            }
        }
        if ($updateOthers && $address->user_id) {
            Address::query()
                ->where('user_id', $address->user_id)
                ->where('id', '<>', $address->id)
                ->update($updateOthers);
        }
        if ($address->is_billing && $address->user_id) {
            $existsDefault = Address::query()
                ->where('user_id', $address->user_id)
                ->where('is_billing', true)
                ->where('is_billing_default', true)
                ->exists();
            if(!$existsDefault){
                $address->update(['is_billing_default' => true]);
            }
        }
    }
}
