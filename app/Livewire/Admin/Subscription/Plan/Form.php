<?php

namespace App\Livewire\Admin\Subscription\Plan;

use App\Models\Permission;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Traits\LivewireTranslatable;
use Livewire\Component;

class Form extends Component
{
    use LivewireTranslatable;

    public $plan;
    public $provider;
    public $providers = [];
    public $countries = [];
    public $planFeatures = [];
    public $frecuencyTypes = [];
    public $types = [];
    public $planFeaturesArray = [];
    public $order;
    public $permissionsArray = [];

    protected function rules(){
        return [
            'plan.title' => 'required',
            'plan.subtitle' => 'nullable',
            'plan.stripe_id' => 'nullable',
            'plan.stripe_product_name' => 'nullable',
            'plan.stripe_price_month_id' => 'nullable',
            'plan.stripe_price_year_id' => 'nullable',
            'plan.amount_month' => 'required',
            'plan.amount_year' => 'required',
            'plan.free_trial_days' => 'nullable',
            'plan.status' => 'nullable',
            'plan.featured' => 'nullable',
            'plan.order' => 'required',
        ];
    }
    protected function messages(){
        return [
            'plan.title.required' => 'Por favor, ingresa el título del plan.',
            'plan.subtitle.nullable' => '',
            'plan.stripe_id.nullable' => '',
            'plan.stripe_product_name.nullable' => '',
            'plan.stripe_price_month_id.nullable' => '',
            'plan.stripe_price_year_id.nullable' => '',
            'plan.amount_month.required' => 'Indica el monto mensual del plan.',
            'plan.amount_year.required' => 'Indica el monto anual del plan.',
            'plan.free_trial_days.nullable' => '',
            'plan.status.nullable' => '',
            'plan.featured.nullable' => '',
            'plan.order.required' => 'Debes establecer el orden de aparición del plan.',
        ];
    }
    public function mount(Plan $plan){
        $this->plan = $plan;
        $this->plan->status = $this->plan->exists ? $this->plan->status : true;
        $this->plan->featured = $this->plan->exists ? $this->plan->featured : false;
        $this->plan->free_trial_days = $this->plan->exists ? $this->plan->free_trial_days : 0;
        $this->order = $plan->order;
        $this->permissionsArray = $this->plan->permissions->pluck('name')->toArray();
        $this->loadPlanFeatures();
    }
    public function render(){
        $this->loadLastOrder();
        $permissions = Permission::getByGroups();
        return view('livewire.admin.subscription.plan.form', compact('permissions'));
    }
    public function save(){
        $this->validate();
        $this->reOrder();
        $this->plan->free_trial_days = ($this->plan->free_trial_days == '' ? null : $this->plan->free_trial_days);
        $this->plan->save();
        $this->savePlanFeatures();
        $this->savePermission();
        $this->dispatch('alert', 'success', __('Registro guardado'));
        $this->dispatch('render');
        if($this->plan->wasRecentlyCreated):
            $this->plan = new Plan(['status' => true]);
        endif;
    }
    public function savePlanFeatures(){ 
        $this->plan->planFeatures()->sync($this->planFeaturesArray);
    }
    public function savePermission(){
        $this->plan->syncPermissions($this->permissionsArray);
    }
    private function loadPlanFeatures(){
        $this->planFeaturesArray = $this->plan->planFeatures()->pluck('plan_feature_id')->toArray();
        $this->planFeatures = PlanFeature::orderBy('id', 'desc')->get();
    }
    private function reOrder(){
        if($this->order != $this->plan->order):
            $plansToOrder = Plan::where('order', '>=', $this->plan->order)->get();
            foreach($plansToOrder as $planToOrder):
                $planToOrder->order = $planToOrder->order + 1;
                $planToOrder->update();
            endforeach;
        endif;
    }
    private function loadLastOrder(){
        if(!$this->plan->order):
            $lastOrder = Plan::latest('order')->first();
            if($lastOrder):
                $this->plan->order = ($lastOrder->order + 1);
            else:
                $this->plan->order = 1;
            endif;
        endif;
    }
}
