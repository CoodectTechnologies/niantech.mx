<?php

namespace App\Http\Middleware;

use App\Models\Product;
use App\Models\ProductVariant;
use Closure;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response|RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        // Definir las instancias de carrito
        $instances = ['default', 'wishlist', 'compare'];
        // Recopilar todos los IDs de productos y variantes de todas las instancias
        $allProductIds = [];
        $allVariantIds = [];
        $cartItemsByInstance = [];

        foreach ($instances as $instance) {
            $cartItems = Cart::instance($instance)->content();
            if (count($cartItems)) {
                $productIds = $cartItems->pluck('id')->toArray();
                $allProductIds = array_merge($allProductIds, $productIds);

                // Recopilar IDs de variantes si existen en las opciones del carrito
                foreach ($cartItems as $item) {
                    if (isset($item->options['variant']['id'])) {
                        $allVariantIds[] = $item->options['variant']['id'];
                    }
                }

                $cartItemsByInstance[$instance] = $cartItems;
            }
        }

        // Si no hay productos en ningún carrito, continuamos sin hacer ninguna consulta
        if (empty($allProductIds)) {
            return $next($request);
        }

        // Obtener los IDs de los productos válidos en una sola consulta
        $validProducts = Product::query()
            ->whereIn('id', $allProductIds)
            ->where('status', '!=', Product::STATUS_DRAFT)
            ->pluck('id')
            ->toArray();

        // Obtener los IDs de las variantes válidas si hay variantes en el carrito
        $validVariants = [];
        if (! empty($allVariantIds)) {
            $validVariants = ProductVariant::query()
                ->whereIn('id', $allVariantIds)
                ->where('is_active', true)
                ->pluck('id')
                ->toArray();
        }

        // Iterar sobre cada instancia y eliminar productos o variantes no válidos
        foreach ($cartItemsByInstance as $instance => $cartItems) {
            foreach ($cartItems as $item) {
                $shouldRemove = false;
                // Verificar si el producto es válido
                if (! in_array($item->id, $validProducts)) {
                    $shouldRemove = true;
                }
                // Si el item tiene variante, verificar que esté activa
                if (! $shouldRemove && isset($item->options['variant']['id'])) {
                    if (! in_array($item->options['variant']['id'], $validVariants)) {
                        $shouldRemove = true;
                    }
                }
                // Eliminar el item si no es válido
                if ($shouldRemove) {
                    Cart::instance($instance)->remove($item->rowId);
                }
            }
        }

        return $next($request);
    }
}
