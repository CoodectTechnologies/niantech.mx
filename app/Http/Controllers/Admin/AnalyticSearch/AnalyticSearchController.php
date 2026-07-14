<?php

namespace App\Http\Controllers\Admin\AnalyticSearch;

use App\Http\Controllers\Controller;

class AnalyticSearchController extends Controller
{
    public function index() {
        return view('admin.analytic-search.index');
    }
}
