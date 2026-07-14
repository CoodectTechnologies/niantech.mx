<?php

namespace App\Http\Controllers\Ecommerce\Account\Address;

use App\Http\Controllers\Controller;
use App\Models\Address;

class AddressController extends Controller
{
    public function __construct() {
        $this->middleware('address')->only('edit');
    }
    public function index() {
        return view('ecommerce.account.address.index');
    }
    public function create() {
        return view('ecommerce.account.address.create');
    }
    public function edit(Address $address) {
        return view('ecommerce.account.address.edit', compact('address'));
    }
}
