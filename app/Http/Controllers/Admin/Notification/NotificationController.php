<?php

namespace App\Http\Controllers\Admin\Notification;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index() {
        return view('admin.notification.index');
    }
}
