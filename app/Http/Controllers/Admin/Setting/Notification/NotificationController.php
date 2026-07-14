<?php

namespace App\Http\Controllers\Admin\Setting\Notification;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index() {
        return view('admin.setting.notification.index');
    }
}
