<?php

namespace App\Livewire\Admin\Dashboard\Order;

use App\Exports\Admin\Dashboard\Order\OrdersExport;
use App\Exports\Admin\Dashboard\Order\OrdersInfoExport;
use App\Models\Comment;
use App\Models\Currency;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $dateStart;
    public $dateEnd;
    public $currencyDefault;

    public function mount(Request $request) {
        if ($request->rangeDateGrapich) {
            $rangeDateGrapich = explode(' - ', $request->rangeDateGrapich);
            $this->dateStart = $rangeDateGrapich[0];
            $this->dateEnd = $rangeDateGrapich[1];
        } else {
            $this->dateStart = Carbon::createFromDate((date('Y')), 01, 01)->format('y-m-d');
            $this->dateEnd = Carbon::createFromDate(date('Y'), 12, 31)->format('y-m-d');
        }
        $this->getReports();
        $this->loadCurrencyDefault();
    }
    public function render() {
        $orders = Order::orderBy('id', 'desc')->get();
        $ordersRecent = $orders->take(10);
        $ordersProcesing = $orders->where('status', 'Procesando');
        $ordersCompleted = $orders->where('status', 'Completado');
        $ordersCancelled = $orders->where('status', 'Cancelado');
        $ordersReturned = $orders->where('status', 'Devolución');
        $productsWithoutImage = $this->getProductsWithoutImage();

        $grapihSalesData = $this->grapihSalesData;

        return view('livewire.admin.dashboard.order.index', compact(
            'ordersRecent',
            'ordersProcesing',
            'ordersCompleted',
            'ordersCancelled',
            'ordersReturned',
            'productsWithoutImage',
            'grapihSalesData'
        ));
    }
    public function getOrderTotalTodayProperty() {
        $orders = Order::query()
            ->validateOrder()
            ->whereDate('created_at', today())
            ->get();
        $total = Order::getConvertCurrencyDefaults($orders, 'total');

        return '$'.number_format($total, 2);
    }
    public function getOrderTotalMonthProperty() {
        $orders = Order::query()
            ->validateOrder()
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->get();
        $total = Order::getConvertCurrencyDefaults($orders, 'total');

        return '$'.number_format($total, 2);
    }
    public function getOrderTotalProperty() {
        $orders = Order::query()
            ->validateOrder()
            ->whereDate('created_at', '>=', $this->dateStart)
            ->whereDate('created_at', '<=', $this->dateEnd)
            ->get();
        $total = Order::getConvertCurrencyDefaults($orders, 'total');

        return '$'.number_format($total, 2);
    }
    public function getOrderTotalDatesProperty() {
        $orders = Order::query()
            ->validateOrder()
            ->whereDate('created_at', '>=', $this->dateStart)
            ->whereDate('created_at', '<=', $this->dateEnd)
            ->get();
        $total = Order::getConvertCurrencyDefaults($orders, 'total');

        return number_format($total, 2);
    }
    public function getGrapihSalesDataProperty() {
        $sales = [];
        $orders = Order::query()
            ->validateOrder()
            ->whereDate('created_at', '>=', $this->dateStart)
            ->whereDate('created_at', '<=', $this->dateEnd)
            ->get();

        foreach ($orders as $order) {
            $date = Carbon::parse($order->created_at)->format('Y-m-d');
            $total = floatval(Order::getConvertCurrencyDefault($order, 'total'));

            if (isset($sales[$date])) {
                $sales[$date] += $total;
            } else {
                $sales[$date] = $total;
            }
        }

        $dates = [];
        $totals = [];

        foreach ($sales as $date => $total) {
            $dates[] = $date;
            $totals[] = $total;
        }

        return [
            'dates' => $dates,
            'totals' => $totals,
        ];
    }
    public function getMostViewedProductsProperty() {
        $products = Product::query()->orderByUniqueViews()->take(10)->get();

        return $products;
    }
    public function getMostSelledProductsProperty() {
        $products = Product::query()
            ->has('orders')
            ->with('orders.products')
            ->get()
            ->sortByDesc(function ($query) {
                return $query->orders->sum('pivot.quantity');
            })
            ->take(10)
            ->map(function ($product) {
                $totalQuantity = $product->orders->sum(function ($order) use ($product) {
                    return $order->products->where('id', $product->id)->first()->pivot->quantity;
                });
                $product->totalSold = $totalQuantity;

                return $product;
            });

        return $products;
    }
    public function getProductsLowStockProperty() {
        $products = Product::select('products.id', 'products.name', 'products.slug')
            ->selectRaw('SUM(product_product_warehouse.quantity) as total_quantity')
            ->validateProduct()
            ->withRelations()
            ->leftJoin('product_product_warehouse', 'products.id', '=', 'product_product_warehouse.product_id')
            ->groupBy('products.id', 'products.name', 'products.slug')
            ->havingRaw('SUM(product_product_warehouse.quantity) <= '.Product::STOCK_LOW)
            ->take(10)
            ->paginate(10, ['*'], 'pageProductLows');

        return $products;
    }
    public function getProductsWithoutImage() {
        return Product::query()
            ->whereNotNull('provider')
            ->whereNotNull('sku')
            ->whereDoesntHave('image')
            ->validateProduct()
            ->paginate(15, ['*'], 'pageProductsWithoutImage');
    }
    public function getProductsPublishedProperty() {
        return Product::query()->where('status', Product::STATUS_PUBLISHED)->orderBy('id', 'desc')->get();
    }
    public function getProductsNoPublishedProperty() {
        return Product::query()->where('status', Product::STATUS_DRAFT)->orderBy('id', 'desc')->get();
    }
    public function getCommentsApprovedProperty() {
        return Comment::where('commentable_type', Product::class)->where('approved', true)->get();
    }
    public function getCommentsNoApprovedProperty() {
        return Comment::where('commentable_type', Product::class)->where('approved', false)->get();
    }
    public function getReports() {
        $reports = ['Todas las ordenes'];
        $productTypes = Product::getTypes();
        $reports = array_merge($reports, $productTypes);

        return $reports;
    }
    public function generateReport($productType) {
        if ($productType == Product::TYPE_PHYSICAL_AND_DIGITAL) {
            $productType = null;
        }
        if (! $productType || in_array($productType, Product::getTypes())) {
            $fileName = $productType ? $productType.'.xlsx' : 'Digitales_Fisicos.xlsx';

            return Excel::download(new OrdersInfoExport($this->dateStart, $this->dateEnd, $productType), $fileName);
        }
        if ($productType == 'Todas las ordenes') {
            $fileName = 'todas_las_ordenes.xlsx';

            return Excel::download(new OrdersExport($this->dateStart, $this->dateEnd), $fileName);
        }
    }
    private function loadCurrencyDefault() {
        $this->currencyDefault = Currency::getDefault();
    }
}
