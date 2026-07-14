<?php

namespace App\Livewire\Ecommerce\Account\Order;

use App\Integrations\ERP;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $user;
    public $ordersInternal = [];
    public $ordersErp = [];

    public function mount() {
        $this->user = User::find(Auth::id());
        $this->loadOrders();
    }
    public function render() {
        return view('livewire.ecommerce.account.order.index');
    }
    private function loadOrders() {
        $ordersInternal = $this->user->orders()->with(['products', 'orderProviders'])->orderByDesc('id')->get();
        foreach ($ordersInternal as $orderInternal) {
            $this->ordersInternal[$orderInternal->number] = [
                'number' => $orderInternal->number,
                'date' => $orderInternal->dateToString(),
                'status' => $orderInternal->statustoString(),
                'total' => $orderInternal->totalToString(),
                'currency' => $orderInternal->currency,
            ];
        }
        if (config('services.erp.status') && $this->user->provider_id) {
            $erp = new ERP;
            $ordersIdsInternalByErp = [];
            foreach ($ordersInternal as $orderInternal) {
                foreach ($orderInternal->orderProviders as $orderProvider) {
                    if ($orderProvider->provider == $erp->code) {
                        $ordersIdsInternalByErp[] = $orderProvider->provider_id;
                        $this->ordersInternal[$orderInternal->number]['so'] = $orderProvider->provider_id;
                    }
                }
            }
            // Obtenemos el client id del erp
            $clientId = $this->user->provider_id;
            // Estas ordenes son cuando ya existian en el ERP, y la ecommerce apenas es nueva
            $ordersErp = $erp->getOrdersByClient($clientId);
            foreach ($ordersErp as $orderErp) {
                if (! in_array($orderErp['so'], $ordersIdsInternalByErp)) {
                    $this->ordersErp[$orderErp['so']] = $orderErp;
                }
            }
            // Sobreescribimos el status interno sobre el status del ERP en dado caso que sean ordenes syncronizadas
            foreach ($this->ordersInternal as $orderInternal) {
                if (isset($orderInternal['so']) && isset($ordersErp[$orderInternal['so']])) {
                    $this->ordersInternal[$orderInternal['number']]['status'] = $ordersErp[$orderInternal['so']]['status'];
                }
            }
        }
    }
}
