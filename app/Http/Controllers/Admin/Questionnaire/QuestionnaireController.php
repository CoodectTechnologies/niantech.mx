<?php

namespace App\Http\Controllers\Admin\Questionnaire;

use App\Http\Controllers\Controller;
use App\Models\Questionnaire;

class QuestionnaireController extends Controller
{
    public function index() {
        return view('admin.questionnaire.index');
    }
    public function create() {
        return view('admin.questionnaire.create');
    }
    public function edit(Questionnaire $questionnaire) {
        return view('admin.questionnaire.edit', compact('questionnaire'));
    }
    public function show(Questionnaire $questionnaire) {
        return view('admin.questionnaire.show', compact('questionnaire'));
    }
}
