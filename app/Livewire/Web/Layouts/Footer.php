<?php

namespace App\Livewire\Web\Layouts;

use App\Models\BlogPost;
use App\Models\PrivacyNotice;
use App\Models\Service;
use Livewire\Component;

class Footer extends Component
{
    public function render() {
        $privacyNotices = PrivacyNotice::getCache();
        $services = Service::getCache()?->take(5);
        $posts = BlogPost::getCache()?->take(5);

        return view('livewire.web.layouts.footer', compact('privacyNotices', 'services', 'posts'));
    }
}
