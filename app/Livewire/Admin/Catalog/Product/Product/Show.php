<?php

namespace App\Livewire\Admin\Catalog\Product\Product;

use App\Models\Currency;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Show extends Component
{
    public $product;
    public $submodule;
    public $currencyDefault;

    public function mount(Product $product, Request $request) {
        $this->product = $product;
        $this->product->load([
            'image',
            'images',
            'productWarehouses',
            'productCategories',
            'comments.user',
            'productSimilars',
            'user',
            'productVariants.productOptionValues.productOption',
            'productVariants.productWarehouses',
        ]);
        $this->submodule = $request->submodule ?? null;
        $this->currencyDefault = Currency::getDefault();
    }
    public function render() {
        $comments = $this->product->comments->sortBy('id');
        $graphicViewsData = $this->getGraphicViewsData();

        return view('livewire.admin.catalog.product.product.show', compact('comments', 'graphicViewsData'));
    }
    private function getGraphicViewsData() {
        $dates = [];
        $totals = [];

        if ($this->product->id) {
            $views = $this->product->views()->select(
                DB::raw('DATE_FORMAT(viewed_at, "%m-%Y") AS month2'),
                DB::raw('DATE_FORMAT(viewed_at, "%b-%Y") AS month'),
                DB::raw('COUNT(id) AS views')
            )
                ->whereYear('viewed_at', date('Y'))
                ->orderBy('month2')
                ->groupBy('month', 'month2')
                ->get();
            foreach ($views as $view) {
                $dates[] = $view->month;
                $totals[] = (int) $view->views;
            }
        }

        return [
            'dates' => $dates,
            'totals' => $totals,
        ];
    }
}
