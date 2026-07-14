<?php

namespace App\Livewire\Admin\Dashboard\Provider;

use App\Integrations\Odoo;
use App\Models\Order;
use App\Services\Synchronizers\Catalog\ProductController;
use App\Services\Synchronizers\Currency\CurrencyController;
use App\Services\Synchronizers\Order\OrderController;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['render'];

    public function render() {
        $ordersWitoutProcesing = $this->getOrdersWitoutProcesing();
        $ordersWitoutPayment = $this->getOrdersWitoutPayment();

        return view('livewire.admin.dashboard.provider.index', compact('ordersWitoutProcesing', 'ordersWitoutPayment'));
    }
    public function getOrdersWitoutProcesing() {
        $orders = Order::query()
            ->with('orderProductWarehouses.productWarehouse')
            ->with('orderProductWarehouses.orderProduct.product')
            ->with('address.state')
            ->whereHas('orderProductWarehouses', function ($query) {
                $query->where('apply_provider', true);
            })
            ->whereDoesntHave('orderProviders')
            ->whereNotNull('payment_method')
            ->when(! config('services.odoo.status'), function ($query) {
                return $query->validateOrder();
            }, function ($query) {
                return $query->whereNotIn('status', [Order::STATUS_CANCELED, Order::STATUS_REFUND]);
            })
            ->get();

        return $orders;
    }
    public function getOrdersWitoutPayment() {
        $orders = Order::query()
            ->validateOrder()
            ->whereHas('orderProviders')
            ->whereHas('orderProductWarehouses', function ($query) {
                $query->where('provider', Odoo::$code);
            })
            ->whereDoesntHave('orderProviderPayment')
            ->get();

        return $orders;
    }
    public function createAndUpdateProducts() {
        $catalog = new ProductController;
        $catalog->save();
        $this->dispatch('alert', 'success', 'Productos sincronizados con éxito');
    }
    public function updateStatusProducts() {
        $catalog = new ProductController;
        $catalog->status();
        $this->dispatch('alert', 'success', 'Productos actualizados con éxito');
    }
    public function updatePriceProducts() {
        $catalog = new ProductController;
        $catalog->prices();
        $this->dispatch('alert', 'success', 'Precios actualizados con éxito');
    }
    public function updateWarehouseProducts() {
        $catalog = new ProductController;
        $catalog->warehouses();
        $this->dispatch('alert', 'success', 'Almacenes actualizados con éxito');
    }
    public function updateContent() {
        $catalog = new ProductController;
        $catalog->content();
        $this->dispatch('alert', 'success', 'Contenidos actualizados con éxito');
    }
    public function syncOrders() {
        $order = new OrderController;
        $order->save();
        $this->dispatch('alert', 'success', 'Ordenes sincronizadas con éxito');
        $this->dispatch('render');
    }
    public function syncExchangeRate() {
        $saved = CurrencyController::saveExchangeRate();
        if ($saved) {
            $this->dispatch('alert', 'success', 'Tipo de cambio sincronizado con éxito');
        } else {
            $this->dispatch('alert', 'warning', 'No se pudo sincronizar el tipo de cambio en este momento');
        }
    }
}
