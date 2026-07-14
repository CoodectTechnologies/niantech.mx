<?php

namespace App\Livewire\Admin\Order\Status;

use App\Http\Controllers\Ecommerce\Checkout\CheckoutController;
use App\Models\Order;
use Exception;
use Livewire\Component;

class Form extends Component
{
    public $order;
    public $statusOld;
    public $paymentStatusOld;

    protected function rules() {
        return [
            'order.payment_status' => 'required',
            'order.status' => 'required',
        ];
    }
    public function mount(Order $order) {
        $this->order = $order;
        $this->order->load('address');
        $this->paymentStatusOld = $this->order->payment_status;
        $this->statusOld = $this->order->status;
    }
    public function render() {
        return view('livewire.admin.order.status.form');
    }
    public function update() {
        $this->validate();
        try {
            if ($this->order->status != $this->statusOld) {
                $this->statusOld = $this->order->status;
                CheckoutController::sendEmailStatus($this->order);
            }
            if (
                $this->paymentStatusOld != Order::PAYMENT_STATUS_APPROVED &&
                $this->order->payment_status == Order::PAYMENT_STATUS_APPROVED
            ) {
                $this->paymentStatusOld = $this->order->payment_status;
                CheckoutController::decrementStock($this->order);
                // CheckoutController::sendProvider($this->order); //Ya no se enviará manualmente, solo por cron
                $this->dispatch('refresh')->to('admin.order.order.show');
            } else {
                if (
                    $this->paymentStatusOld == Order::PAYMENT_STATUS_APPROVED &&
                    $this->order->payment_status != Order::PAYMENT_STATUS_APPROVED
                ) {
                    $this->paymentStatusOld = $this->order->payment_status;
                    CheckoutController::decrementStock($this->order, $reverse = true);
                } else {
                    $this->paymentStatusOld = $this->order->payment_status;
                }
            }
            $this->dispatch('alert', 'success', 'Información actualizada con éxito');
        } catch (Exception $e) {
            $this->dispatch('alert', 'success', 'No se envío el correo de notificación: '.$e->getMessage());
        }
        $this->order->update();
    }
    public function paymentStatuses() {
        return [
            Order::PAYMENT_STATUS_APPROVED,
            Order::PAYMENT_STATUS_PENDING,
            Order::PAYMENT_STATUS_REJECTED,
        ];
    }
    public function statuses() {
        return [
            Order::STATUS_CONFIRMED,
            Order::STATUS_PROCESSING,
            Order::STATUS_SENT,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELED,
            Order::STATUS_REFUND,
        ];
    }
}
