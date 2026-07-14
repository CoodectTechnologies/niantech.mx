<?php

namespace App\Livewire\Ecommerce\Layouts;

use App\Models\PrivacyNotice;
use App\Models\ProductCategory;
use Livewire\Component;

class Footer extends Component
{
    public function render() {
        $privacyNotices = PrivacyNotice::getCache();
        $categoriesFhater = ProductCategory::getCache();

        return view('livewire.ecommerce.layouts.footer', compact('privacyNotices', 'categoriesFhater'));
    }
}
