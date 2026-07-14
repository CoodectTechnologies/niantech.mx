<?php

namespace App\Http\Controllers\Web\PrivacyNotice;

use App\Http\Controllers\Controller;
use App\Models\PrivacyNotice;

class PrivacyNoticeController extends Controller
{
    public function show(PrivacyNotice $privacyNotice) {
        return view('web.privacy-notice.show', compact('privacyNotice'));
    }
}
