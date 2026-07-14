<?php

namespace App\Livewire\Admin\Coupon;

use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Livewire\WithPagination;

class Form extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];
    public $coupon;
    public $method;
    public $couponablesArray = [];
    public $currenciesArray = [];
    public $search;

    protected function rules() {
        return [
            'coupon.code' => 'required|unique:coupons,code,'.$this->coupon->id,
            'coupon.percentage' => $this->coupon->type_amount == 'Porcentaje' ? 'required|min:1|max:99' : 'nullable',
            'coupon.fixed' => $this->coupon->type_amount == 'Fijo' ? 'required' : 'nullable',
            'coupon.date_end' => 'required|date',
            'coupon.minimum_expense' => 'nullable|integer',
            'coupon.exclude_promotion' => 'nullable',
            'coupon.limit_of_use' => 'nullable',
            'coupon.type' => 'required',
            'coupon.conditional' => 'nullable',
            'coupon.type_coupon' => 'required',
            'coupon.active' => 'required',
            'currenciesArray' => 'required|array|min:1',
        ];
    }
    public function mount(Coupon $coupon, $method) {
        $this->coupon = $coupon;
        $this->method = $method;
        $this->coupon->status = $coupon->exists ? $coupon->status : true;
        $this->currenciesArray = $this->coupon->currencies()->validate()->pluck('currency_id')->toArray();
        $this->_loadCouponables();
    }
    public function render() {
        $currencies = Currency::validate()->get();
        $models = $this->_loadModels();

        return view('livewire.admin.coupon.form', compact('currencies', 'models'));
    }
    public function store() {
        $this->validate();
        $this->validateCustom();
        $this->validateNull();
        $this->coupon->save();
        $this->saveCouponables();
        $this->saveCurrencies();
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');

        return Redirect::route('admin.coupon.index');
    }
    public function update() {
        $this->validate();
        $this->validateCustom();
        $this->validateNull();
        $this->coupon->update();
        $this->saveCouponables();
        $this->saveCurrencies();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');

        return Redirect::route('admin.coupon.index');
    }
    private function validateCustom() {
        if ($this->coupon->type != 'Todos') {
            $this->validate([
                'coupon.conditional' => 'required',
                'couponablesArray' => 'required|array|min:1',
            ]);
        }
    }
    private function saveCouponables() {
        if (count($this->couponablesArray)) {
            switch ($this->coupon->type) {
                case 'Categoría':
                    $this->coupon->productCategories()->sync($this->couponablesArray);
                    break;
                case 'Marca':
                    $this->coupon->productBrands()->sync($this->couponablesArray);
                    break;
                case 'Producto':
                    $this->coupon->products()->sync($this->couponablesArray);
                    break;
            }
        }
    }
    private function saveCurrencies() {
        $this->coupon->currencies()->sync($this->currenciesArray);
    }
    private function _loadModels() {
        $models = [];
        if ($this->coupon->type && $this->coupon->type != 'Todos') {
            switch ($this->coupon->type) {
                case 'Producto':
                    $models = Product::orderBy('name');
                    $models = $this->_applyFilter($models);
                    $models = $models->paginate();
                    break;
                case 'Categoría':
                    $models = ProductCategory::orderBy('name');
                    $models = $this->_applyFilter($models);
                    $models = $models->paginate();
                    break;
                case 'Marca':
                    $models = ProductBrand::orderBy('name');
                    $models = $this->_applyFilter($models);
                    $models = $models->paginate();
                    break;
            }
        }

        return $models;
    }
    private function _loadCouponables() {
        if ($this->coupon->type && $this->coupon->type != 'Todos') {
            switch ($this->coupon->type) {
                case 'Producto':
                    $this->couponablesArray = $this->coupon->products()->pluck('couponable_id')->toArray();
                    break;
                case 'Categoría':
                    $this->couponablesArray = $this->coupon->productCategories()->pluck('couponable_id')->toArray();
                    break;
                case 'Marca':
                    $this->couponablesArray = $this->coupon->productBrands()->pluck('couponable_id')->toArray();
                    break;
            }
        }
    }
    public function changeCouponType() {
        $this->couponablesArray = [];
        $this->coupon->conditional = null;
    }
    private function _applyFilter($models) {
        if ($this->search) {
            $models = $models->where('name', 'LIKE', "%{$this->search}%");
            if ($this->coupon->type == 'Producto') {
                $models = $models->orWhere('sku', 'LIKE', "%{$this->search}%");
            }
        }

        return $models;
    }
    private function validateNull() {
        if (! $this->coupon->minimum_expense) {
            $this->coupon->minimum_expense = null;
        }
        if (! $this->coupon->limit_of_use) {
            $this->coupon->limit_of_use = null;
        }
    }

    // UPDATE MAGIC
    public function updatingSearch() {
        $this->resetPage();
    }
}
