<?php

namespace App\Http\Controllers\Admin\Newsletter;

use App\Http\Controllers\Controller;

class NewsletterController extends Controller
{
    public function index() {
        return view('admin.newsletter.index');
    }
}
