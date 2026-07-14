<?php

namespace App\Livewire\Web\Faq;

use App\Models\QuestionAnswer;
use Livewire\Component;

class Index extends Component
{
    public $faqs = [];

    public function mount() {
        $this->loadFaqs();
    }
    public function render() {
        return view('livewire.web.faq.index');
    }
    private function loadFaqs() {
        $this->faqs = QuestionAnswer::getCache();
    }
}
