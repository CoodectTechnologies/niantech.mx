<?php

namespace App\Http\Controllers\Admin\Chatbot;

use App\Http\Controllers\Controller;
use App\Models\Chatbot;

class ChatbotController extends Controller
{
    public function index() {
        return view('admin.chatbot.chatbot.index');
    }
    public function create() {
        return view('admin.chatbot.chatbot.create');
    }
    public function show(Chatbot $chatbot) {
        return view('admin.chatbot.chatbot.show', compact('chatbot'));
    }
    public function edit(Chatbot $chatbot) {
        return view('admin.chatbot.chatbot.edit', compact('chatbot'));
    }
}
