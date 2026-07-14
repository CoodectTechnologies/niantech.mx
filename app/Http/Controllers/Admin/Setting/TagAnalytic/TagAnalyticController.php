<?php

namespace App\Http\Controllers\Admin\Setting\TagAnalytic;

use App\Http\Controllers\Controller;
use App\Models\TagAnalytic;

class TagAnalyticController extends Controller
{
    public function index() {
        $tagAnalytic = TagAnalytic::first() ?? new TagAnalytic;

        return view('admin.setting.tag-analytic.index', compact('tagAnalytic'));
    }
}
