<?php

namespace App\Livewire\Admin\Promotion;

use App\Models\Currency;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\ProductCategory;
use App\Models\Promotion;
use Illuminate\Support\Facades\Redirect;
use Livewire\Component;
use Livewire\WithPagination;

class Form extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $queryString = ['search'];
    public $promotion;
    public $method;
    public $promotionablesArray = [];
    public $currenciesArray = [];
    public $search;

    protected function rules() {
        return [
            'promotion.name' => 'required',
            'promotion.percentage' => 'required|min:1|max:99',
            'promotion.date_start' => 'required|date',
            'promotion.date_end' => 'required|date',
            'promotion.type' => 'required',
            'promotion.conditional' => 'nullable',
            'promotion.active' => 'required',
            'promotion.include_to_variant' => 'required',
        ];
    }
    public function mount(Promotion $promotion, $method) {
        $this->promotion = $promotion;
        $this->method = $method;
        $this->promotion->status = $promotion->exists ? $promotion->status : true;
        $this->currenciesArray = $this->promotion->currencies()->pluck('currency_id')->toArray();
        $this->_loadModels();
        $this->_loadPromotionables();
    }
    public function render() {
        $currencies = Currency::validate()->get();
        $models = $this->_loadModels();

        return view('livewire.admin.promotion.form', compact('currencies', 'models'));
    }
    public function store() {
        $this->validate();
        $this->validateCustom();
        $this->promotion->save();
        $this->savePromotionables();
        $this->saveCurrencies();
        Promotion::regenerateCache();
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
        Redirect::route('admin.promotion.index');
    }
    public function update() {
        $this->validate();
        $this->validateCustom();
        $this->promotion->update();
        $this->savePromotionables();
        $this->saveCurrencies();
        Promotion::regenerateCache();
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
        Redirect::route('admin.promotion.index');
    }
    private function validateCustom() {
        if ($this->promotion->type != 'Todos') {
            $this->validate([
                'promotion.conditional' => 'required',
                'promotionablesArray' => 'required|array|min:1',
            ]);
        }
    }
    private function savePromotionables() {
        if (count($this->promotionablesArray)) {
            switch ($this->promotion->type) {
                case 'Categoría':
                    $this->promotion->productCategories()->sync($this->promotionablesArray);
                    break;
                case 'Marca':
                    $this->promotion->productBrands()->sync($this->promotionablesArray);
                    break;
                case 'Producto':
                    $this->promotion->products()->sync($this->promotionablesArray);
                    break;
            }
        }
    }
    private function saveCurrencies() {
        $this->promotion->currencies()->sync($this->currenciesArray);
    }
    private function _loadModels() {
        $models = [];
        if ($this->promotion->type && $this->promotion->type != 'Todos') {
            switch ($this->promotion->type) {
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
    private function _loadPromotionables() {
        if ($this->promotion->type && $this->promotion->type != 'Todos') {
            switch ($this->promotion->type) {
                case 'Producto':
                    $this->promotionablesArray = $this->promotion->products()->pluck('promotionable_id')->toArray();
                    break;
                case 'Categoría':
                    $this->promotionablesArray = $this->promotion->productCategories()->pluck('promotionable_id')->toArray();
                    break;
                case 'Marca':
                    $this->promotionablesArray = $this->promotion->productBrands()->pluck('promotionable_id')->toArray();
                    break;
            }
        }
    }
    public function changePromotionType() {
        $this->promotionablesArray = [];
        $this->promotion->conditional = null;
    }
    private function _applyFilter($models) {
        if ($this->search) {
            $models = $models->where('name', 'LIKE', "%{$this->search}%");
        }

        return $models;
    }

    // UPDATE MAGIC
    public function updatingSearch() {
        $this->resetPage();
    }
}
