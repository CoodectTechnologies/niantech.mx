<?php

namespace App\Livewire\Admin\Setting\TagAnalytic;

use App\Models\TagAnalytic;
use Livewire\Component;

class Form extends Component
{
    public $tagAnalytic;
    public $method;

    protected function rules() {
        return [
            'tagAnalytic.header' => 'nullable',
            'tagAnalytic.footer' => 'nullable',
        ];
    }
    public function mount(TagAnalytic $tagAnalytic, $method) {
        $this->tagAnalytic = $tagAnalytic;
        $this->method = $method;
    }
    public function render() {
        return view('livewire.admin.setting.tag-analytic.form');
    }
    public function update() {
        $this->validate();
        $this->tagAnalytic->save();
        $this->_refreshCache();
        $this->dispatch('alert', 'success', __('Registration successfully added'));
        $this->dispatch('render');
    }
    private function _refreshCache() {
        TagAnalytic::regenerateCache();
    }
}
