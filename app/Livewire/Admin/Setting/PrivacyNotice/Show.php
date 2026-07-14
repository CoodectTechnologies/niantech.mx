<?php

namespace App\Livewire\Admin\Setting\PrivacyNotice;

use App\Models\PrivacyNotice;
use Livewire\Component;

class Show extends Component
{
    public $privacyNotice;

    public function mount(PrivacyNotice $privacyNotice) {
        $this->privacyNotice = $privacyNotice;
    }
    public function render() {
        return view('livewire.admin.setting.privacy-notice.show');
    }
}
