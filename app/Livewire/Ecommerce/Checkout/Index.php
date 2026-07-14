<?php

namespace App\Livewire\Ecommerce\Checkout;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\ShippingZone;
use App\Models\User;
use App\Services\Cart\CartService;
use App\Services\Coupon\CouponService;
use App\Services\Order\OrderService;
use App\Services\Shipping\ShippingService;
use Carbon\Carbon;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['address-saved' => 'addressSaved'];

    // Instances models
    public ?User $user = null;
    public ?Address $address = null;
    public ?Address $billingAddress = null;
    public ?Coupon $coupon = null;

    // Addresses
    public Collection|array|null $addresses = [];
    public Collection|array|null $billingAddresses = [];

    // Shipping
    public ?int $shippingZoneId = null;
    public ?string $shippingMethod = null;
    public ?string $shippingMethodProviderId = null;
    public array $shippingMethods = [];

    // Coupon
    public ?string $couponCode = null;
    public float $couponPriceDiscount = 0;
    public float $couponPercentageDiscount = 0;

    // Tools
    public bool $showMoreAddresses = false;
    public bool $showMoreBillingAddresses = false;
    public bool $addressDiferentCreate = false;
    public bool $billingRequire = false;
    public bool $billingAddressCreate = false;
    public bool $billingAddressDiferentCreate = false;
    public bool $couponRequire = false;
    public bool $shippingRequire = false;
    public ?string $shippingDays = null;

    // Prices
    public float $subtotal = 0;
    public float $subtotalTax = 0;
    public float $subtotalFinal = 0;
    public float $shippingPrice = 0;
    public float $shippingPriceTax = 0;
    public float $shippingPriceFinal = 0;
    public float $tax = 0;
    public float $totalPrice = 0;

    protected function rules() {
        return [
            'address.id' => 'required|exists:addresses,id',
            'billingAddress.id' => $this->billingRequire ? 'required|exists:addresses,id' : 'nullable',
            'coupon.id' => $this->couponRequire ? 'required|exists:coupons,id' : 'nullable',
            'shippingZoneId' => $this->shippingRequire ? 'required|exists:shipping_zones,id' : 'nullable',
        ];
    }
    public function mount() {
        $this->loadUser();
        $this->loadShippingRequire();
        $this->loadAddress();
        $this->loadAddresses();
        $this->loadBillingAddress();
        $this->loadBillingAddresses();
        $this->loadShippingMethods();
        $this->loadPrices();
    }
    public function render() {
        return view('livewire.ecommerce.checkout.index');
    }

    // ADDRESS
    public function loadAddress($addressId = null) {
        $this->resetShipping();
        if ($addressId) {
            $this->address = Address::with('state.country')->find($addressId);
            $this->showMoreAddresses = false;
            $this->loadShippingMethods();
        } else {
            if ($this->user) {
                $this->address = $this->user->addressDefect() ?? new Address;
            }
        }
    }
    private function loadAddresses() {
        if ($this->user) {
            $this->addresses = $this->user->addresses()->with('state.country')->validate()->get();
        }
    }

    // ADDRESS - BILLING
    public function loadBillingAddress($addressId = null) {
        if ($addressId) {
            $this->billingAddress = Address::with('state.country')->where('is_billing', true)->where('id', $addressId)->first();
            $this->showMoreBillingAddresses = true;
        } else {
            if ($this->user) {
                $this->billingAddress = $this->user->billingAddressDefect() ?? new Address;
            }
        }
    }
    private function loadBillingAddresses() {
        if ($this->user) {
            $this->billingAddresses = $this->user->addresses()->with('state.country')->where('is_billing', true)->validate()->get();
        }
    }

    // ADDRESS - REPLICATE INFO BILLING
    public function replicateShippingAddressToBillingAddress() {
        if ($this->address->exists ?? false) {
            $this->dispatch('fill-billing-address', id: $this->address->id, target: 'billing.create')->to('ecommerce.address.form');
        }
    }
    public function addressSaved(int $id, ?string $target = null) {
        switch ($target) {
            case 'shipping.create':
            case 'shipping.create.diferent':
                $this->loadAddress($id);
                $this->loadAddresses();
                $this->addressDiferentCreate = false;
                if ($this->address->is_billing) {
                    $this->billingRequire = true;
                    $this->loadBillingAddress($this->address->id);
                    $this->loadBillingAddresses();
                }
                break;
            case 'billing.create':
            case 'billing.create.diferent':
                $this->billingRequire = false;
                $this->billingAddressCreate = false;
                $this->billingAddressDiferentCreate = false;
                $this->loadBillingAddress($id);
                $this->loadAddresses();
                $this->loadBillingAddresses();
                break;
        }
    }

    // SHIPPING - REQUIRE
    private function loadShippingRequire() {
        $this->shippingRequire = (new ShippingService)->applyShipping('default');
    }

    // SHIPPING - METHODS
    private function loadShippingMethods() {
        $stateId = null;
        $zipCode = null;
        $zipCodeRangeValid = range(5, 7);
        if (($this->address?->exists ?? false) && (in_array(strlen($this->address->zip_code), $zipCodeRangeValid))) {
            $stateId = $this->address->state_id;
            $zipCode = $this->address->zip_code;
            $this->shippingMethods = ShippingService::getShippingMethods($stateId, $zipCode);
        } else {
            $this->shippingMethods = [];
        }
    }
    public function updatedShippingZoneId($id) {
        // if(config('services.odoo.status')):
        if (config('services.odoo.status') && false) {
            // TODO: BORRAME EL false y desarrolla lo de los métodos de envío de ODOO
            $shippingInfo = $this->shippingMethods[$id];
            $this->shippingPrice = $shippingInfo['price'];
            $this->shippingMethod = $shippingInfo['name'];
            $this->shippingMethodProviderId = $shippingInfo['id'];
            if ($shippingInfo['shipping_days']) {
                $days = $shippingInfo['shipping_days'];
                $estimatedDate = Carbon::parse(today())->addDays($shippingInfo['shipping_days'])->toFormattedDateString();
                $this->shippingDays = $days.' '.__('days').', '.$estimatedDate;
            }
        } else {
            $shippingZone = ShippingZone::findOrFail($id);
            $this->shippingPrice = ShippingService::getShippingPriceByZone($shippingZone);
            $this->shippingMethod = $shippingZone->alias;
            if ($shippingZone->shipping_days) {
                $days = $shippingZone->shipping_days;
                $estimatedDate = Carbon::parse(today())->addDays($shippingZone->shipping_days)->toFormattedDateString();
                $this->shippingDays = $days.' '.__('days').', '.$estimatedDate;
            }
        }
        $this->loadPrices();
    }

    // PRICES
    private function loadPrices() {
        $this->subtotal = floatval(str_replace(config('cart.format.thousand_seperator'), '', Cart::instance('default')->subtotal()));
        $this->subtotalTax = floatval(str_replace(config('cart.format.thousand_seperator'), '', Cart::instance('default')->tax()));
        $this->shippingPrice = floatval(str_replace(config('cart.format.thousand_seperator'), '', $this->shippingPrice));
        if (config('cart.tax')) {
            $this->shippingPriceTax = ($this->shippingPrice * (config('cart.tax') / 100));
            if (config('cart.shipping_methods_already_include_tax')) {
                $this->shippingPrice = ($this->shippingPrice - $this->shippingPriceTax);
            }
        }
        $this->subtotalFinal = $this->subtotal + $this->subtotalTax;
        $this->shippingPriceFinal = $this->shippingPrice + $this->shippingPriceTax;
        $this->tax = $this->subtotalTax + $this->shippingPriceTax;
        $this->totalPrice = (($this->subtotal + $this->shippingPrice + $this->tax) - $this->couponPriceDiscount);
    }

    // COUPON - APPLY
    public function applyCoupon() {
        if (! $this->couponRequire) {
            $this->resetCoupon();

            return;
        }
        $this->validate(['couponCode' => 'required']);
        try {
            $couponService = new CouponService;
            $result = $couponService->apply($this->couponCode, $this->subtotal);
            $this->coupon = $result['coupon'];
            $this->couponPriceDiscount = $result['price_discount'];
            $this->couponPercentageDiscount = $result['percentage_discount'];
            $this->loadPrices();
            $this->dispatch('alert', 'success', __('Coupon applied'), 'Se aplicó un descuento del '.$this->couponPercentageDiscount);
        } catch (Exception $e) {
            $this->addError('couponCode', $e->getMessage());
            $this->resetCoupon();
        }
    }

    // ORDER - CREATE
    public function createOrder() {
        try {
            CartService::validate(Cart::instance('default'));
        } catch (Exception $e) {
            Session::flash('alert', $e->getMessage());
            Session::flash('alert-type', 'error');

            return;
        }
        $this->validate();
        try {
            $orderService = new OrderService;
            $order = $orderService->create(
                user: $this->user,
                address: $this->address,
                cart: Cart::instance('default'),
                data: [
                    'subtotal' => $this->subtotal,
                    'subtotalTax' => $this->subtotalTax,
                    'subtotalFinal' => ($this->subtotal + $this->subtotalTax),
                    'shippingPrice' => $this->shippingPrice,
                    'shippingPriceTax' => $this->shippingPriceTax,
                    'shippingPriceFinal' => $this->shippingPrice + $this->shippingPriceTax,
                    'shippingMethod' => $this->shippingMethod,
                    'shippingMethodProviderId' => $this->shippingMethodProviderId,
                    'shippingDays' => $this->shippingDays,
                    'couponPriceDiscount' => $this->couponPriceDiscount,
                    'couponPercentageDiscount' => $this->couponPercentageDiscount,
                    'tax' => $this->tax,
                    'totalPrice' => $this->totalPrice,
                ],
                billingAddress: $this->billingRequire ? $this->billingAddress : null,
                coupon: $this->coupon ?? null,
            );

            return Redirect::route('ecommerce.checkout.payment', $order);
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'warning', __('An error occurred while creating the order'));
        }
    }

    // USER
    private function loadUser() {
        if (Auth::check()) {
            $this->user = User::find(Auth::id());
            $this->user->load(['addresses.state.country']);
        }
    }

    // RESETS
    private function resetShipping() {
        if ($this->shippingRequire) {
            $this->reset('shippingPrice', 'totalPrice', 'shippingDays', 'shippingZoneId', 'shippingMethod', 'shippingMethods');
        }
    }
    private function resetCoupon() {
        $this->reset('coupon', 'couponPriceDiscount', 'couponPercentageDiscount');
        $this->loadPrices();
    }
}
