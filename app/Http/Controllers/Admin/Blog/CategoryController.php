<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index() {
        return view('admin.blog.category.index');
    }
}
