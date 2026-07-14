<?php

namespace App\Http\Controllers\Ecommerce\PrivacyNotice;

use App\Http\Controllers\Controller;
use App\Models\PrivacyNotice;

class PrivacyNoticeController extends Controller
{
    public function show(PrivacyNotice $privacyNotice) {
        return view('ecommerce.privacy-notice.show', compact('privacyNotice'));
    }
}
