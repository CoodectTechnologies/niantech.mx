<?php

namespace App\Http\Controllers\Admin\Catalog;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;

class CategoryController extends Controller
{
    public function index() {
        return view('admin.catalog.category.index');
    }
    public function create() {
        return view('admin.catalog.category.create');
    }
    public function edit(ProductCategory $category) {
        return view('admin.catalog.category.edit', compact('category'));
    }
    public function sortlist() {
        return view('admin.catalog.category.sortlist');
    }
}
