<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends Model
{
    use HasFactory;
    use LogsActivity;

    public const STATUS_CONFIRMED = 'Confirmado';
    public const STATUS_PROCESSING = 'Procesando';
    public const STATUS_SENT = 'Enviado';
    public const STATUS_COMPLETED = 'Completado';
    public const STATUS_CANCELED = 'Cancelado';
    public const STATUS_REFUND = 'Devolución';
    public const PAYMENT_STATUS_APPROVED = 'Aprobado';
    public const PAYMENT_STATUS_PENDING = 'Pendiente';
    public const PAYMENT_STATUS_REJECTED = 'Rechazado';

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions {
        return LogOptions::defaults()
            ->useLogName('Orden')
            ->setDescriptionForEvent(fn (string $eventName) => "Una orden ha sido {$eventName}")
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty()
            ->logAll();
    }
    public function getRouteKeyName() {
        return 'number';
    }
    public function currency() {
        return $this->belongsTo(Currency::class);
    }
    public function products() {
        return $this->belongsToMany(Product::class)->withTimestamps()->withPivot(['product_variant_id', 'type', 'quantity', 'price', 'subtotal', 'created_at']);
    }
    public function orderProducts() {
        return $this->hasMany(OrderProduct::class)->with(['productVariant.productOptionValues.productOption']);
    }
    public function orderProductWarehouses() {
        return $this->hasManyThrough(OrderProductWarehouse::class, OrderProduct::class, 'order_id', 'order_product_id');
    }
    public function orderProviders() {
        return $this->hasMany(OrderProvider::class);
    }
    public function orderProviderErrors() {
        return $this->hasMany(OrderProviderError::class);
    }
    public function orderProviderPayment() {
        return $this->hasOne(OrderProviderPayment::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function address() {
        return $this->belongsTo(Address::class);
    }
    public function billingAddress() {
        return $this->belongsTo(Address::class, 'billing_address_id')->where('is_billing', true);
    }
    public function invoice() {
        return $this->hasOne(Invoice::class);
    }
    public function coupon() {
        return $this->belongsTo(Coupon::class);
    }
    public function orderTrackings() {
        return $this->hasMany(OrderTracking::class);
    }
    public function totalToString() {
        return currencySymbol($this->currency).number_format($this->total, 2).' '.$this->currency;
    }
    public function subtotalToString() {
        return currencySymbol($this->currency).number_format($this->subtotal, 2).' '.$this->currency;
    }
    public function subtotalTaxToString() {
        return currencySymbol($this->currency).number_format($this->subtotal_tax, 2).' '.$this->currency;
    }
    public function shippingPriceToString() {
        return currencySymbol($this->currency).number_format($this->shipping_price, 2).' '.$this->currency;
    }
    public function shippingPriceTaxToString() {
        return currencySymbol($this->currency).number_format($this->shipping_price_tax, 2).' '.$this->currency;
    }
    public function taxToString() {
        return currencySymbol($this->currency).number_format($this->tax, 2).' '.$this->currency;
    }
    public function hasProductProvider() {
        $hasProductProvider = false;
        foreach ($this->products as $product) {
            if ($product->provider) {
                $hasProductProvider = true;
                break;
            }
        }

        return $hasProductProvider;
    }
    public function statusToString() {
        $status = '';
        switch ($this->status) {
            case 'Confirmado':
                $status = '<div class="badge badge-success">'.$this->status.'</div>';
                break;
            case 'Procesando':
                $status = '<div class="badge badge-success">'.$this->status.'</div>';
                break;
            case 'Enviado':
                $status = '<div class="badge badge-primary">'.$this->status.'</div>';
                break;
            case 'Completado':
                $status = '<div class="badge badge-primary">'.$this->status.'</div>';
                break;
            case 'Devolución':
                $status = '<div class="badge badge-info">'.$this->status.'</div>';
                break;
            case 'Cancelado':
                $status = '<div class="badge badge-danger">'.$this->status.'</div>';
                break;
            default:
                $status = '<div class="badge badge-warning">Status no encontrado</div>';
                break;
        }

        return $status;
    }
    public function paymentStatusToString() {
        $paymentStatus = '';
        switch ($this->payment_status) {
            case 'Aprobado':
                $paymentStatus = '<div class="badge badge-success">'.$this->payment_status.'</div>';
                break;
            case 'Pendiente':
                $paymentStatus = '<div class="badge badge-warning">'.$this->payment_status.'</div>';
                break;
            case 'Rechazado':
                $paymentStatus = '<div class="badge badge-danger">'.$this->payment_status.'</div>';
                break;
            default:
                $paymentStatus = '<div class="badge badge-warning">Status no encontrado</div>';
                break;
        }

        return $paymentStatus;
    }
    public function productsProrate() {
        $totalOrderPrice = $this->subtotal_final;
        $couponPricePercentage = intval($this->coupon_price_discount);
        $products = [];
        foreach ($this->products as $product) {
            $price = $product->pivot->price; // Precio original del producto
            $quantity = $product->pivot->quantity;
            $sku = $product->sku;
            $name = $product->name;
            if ($couponPricePercentage) {
                // Calculamos el total del producto sin descuento
                $totalProductPrice = $price * $quantity;
                // Calculamos el porcentaje del total del descuento que se le aplicará a este producto
                $productDiscount = ($totalProductPrice / $totalOrderPrice) * $couponPricePercentage;
                // Calcular el precio después de aplicar el descuento proporcional
                $discountedPrice = $totalProductPrice - $productDiscount;
                // Dividimos entre la cantidad
                $price = round($discountedPrice / $quantity, 0);
            }
            $products[$product->id] = [
                'name' => $name,
                'detail' => $product->detail,
                'sku' => $sku,
                'quantity' => $quantity,
                'price' => $price,
                'image' => url($product->imagePreview()),
            ];
        }

        return $products;
    }
    public function getProvidersCode() {
        $providersCode = [];
        foreach ($this->orderProductWarehouses as $orderProductWarehouse) {
            if (! in_array($orderProductWarehouse->provider, $providersCode)) {
                $providersCode[] = $orderProductWarehouse->provider;
            }
        }

        return $providersCode;
    }
    public static function getConvertCurrencyDefaults($orders, $attribute) {
        $convert = 0;
        foreach ($orders as $order) {
            $convert += self::getConvertCurrencyDefault($order, $attribute);
        }

        return $convert;
    }
    public static function getConvertCurrencyDefault($order, $attribute) {
        $convert = 0;
        $currencyDefault = Currency::getDefault();
        if ($order->currency == $currencyDefault->code) {
            $convert = floatval($order->$attribute / $currencyDefault->value);
        } else {
            $currency = Currency::getCurrencyByCode($order->currency);
            $currencyValue = $order->currency_value;
            if ($currency->value != $order->currency_value) {
                $currencyValue = $currency->value;
            }
            $convert = floatval($order->$attribute * $currencyValue);
        }

        return $convert;
    }
    public function dateToString() {
        return Carbon::parse($this->created_at)->toFormattedDateString();
    }

    // Scopes
    public function scopeValidateOrder($query) {
        return $query->whereNotIn('status', [Order::STATUS_CANCELED, Order::STATUS_REFUND])
            ->where('payment_status', Order::PAYMENT_STATUS_APPROVED);
    }
}
