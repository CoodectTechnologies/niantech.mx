<?php

namespace App\Http\Controllers\Admin\Setting\AccessCaptcha;

use App\Http\Controllers\Controller;

class AccessCaptchaController extends Controller
{
    public function index() {
        return view('admin.setting.access-captcha.index');
    }
}
