<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public function index() {
        return view('admin.blog.tag.index');
    }
}
