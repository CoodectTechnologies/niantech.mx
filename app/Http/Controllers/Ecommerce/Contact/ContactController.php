<?php

namespace App\Http\Controllers\Ecommerce\Contact;

use App\Http\Controllers\Controller;
use App\Models\QuestionAnswer;

class ContactController extends Controller
{
    public function index() {
        $questionAnswers = QuestionAnswer::orderByDesc('id')->get();

        return view('ecommerce.contact.index', compact('questionAnswers'));
    }
}
