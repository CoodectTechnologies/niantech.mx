<?php

namespace App\Http\Controllers\Admin\Setting\PrivacyNotice;

use App\Http\Controllers\Controller;
use App\Models\PrivacyNotice;

class PrivacyNoticeController extends Controller
{
    public function index() {
        return view('admin.setting.privacy-notice.index');
    }
    public function create() {
        return view('admin.setting.privacy-notice.create');
    }
    public function edit(PrivacyNotice $privacyNotice) {
        return view('admin.setting.privacy-notice.edit', compact('privacyNotice'));
    }
    public function show(PrivacyNotice $privacyNotice) {
        return view('admin.setting.privacy-notice.show', compact('privacyNotice'));
    }
}
