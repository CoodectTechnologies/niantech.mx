<?php

namespace App\Console\Commands\Admin\Cart;

use App\Mail\Cart\CartForgotten as CartCartForgotten;
use App\Models\Product;
use App\Models\Shoppingcart;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CartForgotten extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:forgotten';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail to all users who have forgotten cart';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {
        $countMaxNotification = 1;
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $shoppingCarts = Shoppingcart::query()
            ->where('instance', 'default')
            ->whereNotNull('identifier')
            ->whereDate('created_at', '>=', $startOfWeek)
            ->whereDate('created_at', '<=', $endOfWeek)
            ->get();

        foreach ($shoppingCarts as $shoppingCart) {
            $shoppingCartCountForgotten = intval($shoppingCart->count_forgotten);
            if ($shoppingCartCountForgotten >= $countMaxNotification) {
                continue;
            }
            $products = [];
            $items = unserialize($shoppingCart->content);
            foreach ($items as $item) {
                $product = Product::where('id', $item->id)->first();
                if ($product && $product->status == Product::STATUS_PUBLISHED) {
                    $products[] = $product;
                }
            }
            if (count($products)) {
                $user = User::where('id', $shoppingCart->identifier)->first();
                if ($user) {
                    try {
                        Mail::to($user->email)->send(new CartCartForgotten($products));
                        $shoppingCart->update([
                            'count_forgotten' => $shoppingCartCountForgotten + 1,
                        ]);
                    } catch (Exception $e) {
                        report($e);
                        $this->error('Error enviando email a '.$user->email.': '.$e->getMessage());
                    }
                }
            }
        }

        return 0;
    }
}
