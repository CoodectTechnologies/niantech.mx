<?php

namespace App\Livewire\Admin\Setting\PrivacyNotice;

use App\Models\PrivacyNotice;
use Exception;
use Livewire\Component;

class Index extends Component
{
    protected $listeners = ['render'];

    public function render() {
        $privacyNotices = PrivacyNotice::query()->orderBy('order')->get();

        return view('livewire.admin.setting.privacy-notice.index', compact('privacyNotices'));
    }
    public function destroy(PrivacyNotice $privacyNotice) {
        try {
            $privacyNotice->delete();
            PrivacyNotice::regenerateCache();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
