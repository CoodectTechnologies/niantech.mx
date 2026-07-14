<?php

namespace App\Http\Controllers\Admin\Setting\Backup;

use App\Http\Controllers\Controller;

class BackupController extends Controller
{
    public function index() {
        return view('admin.setting.backup.index');
    }
}
