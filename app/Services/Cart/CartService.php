<?php

namespace App\Services\Cart;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\Inventory\InventoryService;
use Exception;
use Gloudemans\Shoppingcart\Cart;
use Gloudemans\Shoppingcart\Facades\Cart as CartFacade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected static ?InventoryService $inventoryService = null;

    public function __construct() {
        $this->inventoryService = new InventoryService;
    }
    public static function add($product, $qty, $price, $options = []) {
        self::validateOptions($product, $options);
        self::validateInventory($product, $qty, 'store', $options);
        self::validateItem($product, $options);

        $priceWholesale = self::priceWholesale($product, $qty, $options['price']);
        $price = ($price - $priceWholesale);
        $cartItems = self::findCartItems($product, $options);

        if (! $cartItems->isNotEmpty()) {
            CartFacade::instance('default')->add([
                'id' => $product->id,
                'name' => $product->getName(),
                'qty' => $qty,
                'price' => $price,
                'options' => $options,
            ])->associate(Product::class);
            self::saveSession();
        } else {
            $cartItem = $cartItems->first();

            return self::update($product, $cartItem->rowId, ($qty + $cartItem->qty));
        }
    }
    public static function update($product, $rowId, $qty) {
        $cart = CartFacade::instance('default')->get($rowId);
        self::validateOptions($product, $cart->options);
        self::validateInventory($product, $qty, 'update', $cart->options);
        self::validateItem($product, $cart->options);

        $priceWholesale = self::priceWholesale($product, $qty, $cart->options->price);
        $price = ($cart->options->price - $priceWholesale);

        CartFacade::instance('default')->update($rowId, ['qty' => $qty, 'price' => $price]);
        self::saveSession();
    }
    public static function destroy($rowId) {
        try {
            CartFacade::instance('default')->remove($rowId);
            self::saveSession();
            Session::flash('alert', __('Article was successfully removed'));
            Session::flash('alert-type', 'success');
        } catch (Exception $e) {
            report($e);
        }
    }
    public static function validate(Cart $cart): void {
        if (! $cart->count()) {
            throw new Exception(__('Without products'));
        }
        foreach ($cart->content() as $item) {
            $product = $item->model;
            self::validateOptions($product, $item->options);
            self::validateInventory($product, $item->qty, null, $item->options);
            self::validateItem($item->model, $item->options);
        }
    }
    private static function validateOptions($product, $options): void {
        if (! isset($options['variant']) && $product->productVariants()->count()) {
            throw new Exception(__('This product has variations, please select the options indicated'));
        }
    }
    private static function validateInventory($product, $qtyToAdd, $method, $options): void {
        $type = $options['type'];
        if ($type == Product::TYPE_DIGITAL) {
            return;
        }

        if ($method == 'store') {
            $cartItems = self::findCartItems($product, $options);
            if ($cartItems->isNotEmpty()) {
                $qtyToAdd += $cartItems->first()->qty;
            }
        }

        $availableQuantity = self::inventory()->getAvailableQuantity($product, $options['variant']['id'] ?? null);
        if ($availableQuantity < $qtyToAdd) {
            throw new Exception(__('Stock limit exceeded, maximun:').' '.$availableQuantity);
        }
    }
    private static function validateItem($product, $options) {
        self::validateProduct($product);
        if (isset($options['variant']['id'])) {
            self::validateProductVariant($product, $options);
        }
    }
    private static function validateProduct($product) {
        if (! $product || ! $product->status) {
            throw new Exception(__('The product :product is no longer available', ['product' => $product->name]));
        }
    }
    private static function validateProductVariant($product, $options): void {
        $variant = ProductVariant::find($options['variant']['id']);
        if (! $variant) {
            throw new Exception(__('The variant of :product is no longer available', ['product' => $product->name]));
        }
        if (! $variant->is_active) {
            throw new Exception(__('The variant of :product is no longer active', ['product' => $product->name]));
        }
    }
    private static function priceWholesale($product, $qty, $price) {
        $priceWholesale = 0;
        if ($wholesale = $product->getWholesale()) {
            foreach ($wholesale->wholesaleDetails as $wholesaleDetail) {
                if (in_array($qty, range($wholesaleDetail->qty_from, $wholesaleDetail->qty_to))) {
                    $priceWholesale = ($price * $wholesaleDetail->percentage / 100);
                    break;
                }
            }
        }

        return $priceWholesale;
    }
    private static function saveSession(): void {
        if (Auth::check()) {
            CartFacade::instance('default')->store(Auth::id());
        }
    }
    private static function findCartItems($product, $options) {
        return CartFacade::instance('default')->search(function ($cartItem) use ($product, $options) {
            if (isset($options['variant'])) {
                return isset($cartItem->options['variant'])
                    && $cartItem->options['variant']['id'] === $options['variant']['id'];
            }

            return $cartItem->id === $product->id
                && ! isset($cartItem->options['variant']);
        });
    }
    private static function inventory(): InventoryService {
        return self::$inventoryService ??= new InventoryService;
    }
}
