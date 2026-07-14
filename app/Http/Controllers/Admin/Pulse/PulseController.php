<?php

namespace App\Http\Controllers\Admin\Pulse;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PulseController extends Controller
{
    public function __invoke(Request $request) {
        return view('admin.pulse.index');
    }
}
