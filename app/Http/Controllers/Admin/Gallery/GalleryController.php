<?php

namespace App\Http\Controllers\Admin\Gallery;

use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    public function index() {
        return view('admin.gallery.index');
    }
}
