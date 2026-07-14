<?php

namespace App\Livewire\Admin\Setting\PrivacyNotice;

use App\Models\PrivacyNotice;
use App\Traits\LivewireTranslatable;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Form extends Component
{
    use LivewireTranslatable;

    public $privacyNotice;
    public $method;

    // Tools
    public $order;

    protected function rules() {
        return [
            'translations.name.'.translatable() => 'required',
            'privacyNotice.order' => 'required',
            'translations.content.'.translatable() => 'required',
        ];
    }
    public function mount(PrivacyNotice $privacyNotice, $method) {
        $this->privacyNotice = $privacyNotice;
        $this->method = $method;
        $this->order = $privacyNotice->order;
        $this->loadLastOrder();
        $this->loadTranslations($this->privacyNotice);
    }
    public function render() {
        $privacyNotices = PrivacyNotice::orderBy('id', 'desc')->get();

        return view('livewire.admin.setting.privacy-notice.form', compact('privacyNotices'));
    }
    public function store() {
        $this->validate();
        $this->reOrder();
        $this->saveTranslations($this->privacyNotice);
        $this->privacyNotice->save();
        $this->regenerateCache();
        $this->privacyNotice = new PrivacyNotice;
        Session::flash('alert', __('Registration successfully added'));
        Session::flash('alert-type', 'success');

        return Redirect::route('admin.setting.privacy-notice.create');
    }
    public function update() {
        $this->validate();
        $this->reOrder();
        $this->saveTranslations($this->privacyNotice);
        $this->privacyNotice->update();
        $this->regenerateCache();
        $this->dispatch('alert', 'success', __('Registration successfully updated'));
        $this->dispatch('render');

        return Redirect::route('admin.setting.privacy-notice.show', $this->privacyNotice);
    }
    private function reOrder() {
        if ($this->order != $this->privacyNotice->order) {
            $reOrder = PrivacyNotice::where('order', $this->privacyNotice->order)->where('id', '<>', $this->privacyNotice->id)->first();
            if ($reOrder) {
                $privacyNoticesToOrders = PrivacyNotice::where('order', '>=', $this->privacyNotice->order);
                if ($this->privacyNotice->exists) {
                    $privacyNoticesToOrders = $privacyNoticesToOrders->where('id', '<>', $this->privacyNotice->id);
                }
                $privacyNoticesToOrders->increment('order');
            }
        }
    }
    public function loadLastOrder() {
        if (! $this->privacyNotice->exists) {
            $lastOrder = PrivacyNotice::latest('order');
            $lastOrder = $lastOrder->first();
            if ($lastOrder) {
                $this->privacyNotice->order = ($lastOrder->order + 1);
            } else {
                $this->privacyNotice->order = 1;
            }
        }
    }
    private function regenerateCache() {
        PrivacyNotice::regenerateCache();
    }
}
