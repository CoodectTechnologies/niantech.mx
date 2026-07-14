<?php

namespace App\Http\Controllers\Admin\QuestionAnswer;

use App\Http\Controllers\Controller;

class QuestionAnswerController extends Controller
{
    public function index() {
        return view('admin.question-answer.index');
    }
}
