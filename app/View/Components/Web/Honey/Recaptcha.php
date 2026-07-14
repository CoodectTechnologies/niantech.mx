<?php

namespace App\View\Components\Web\Honey;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Recaptcha extends Component
{
    public $action;
    public $status;

    public function __construct($action = null) {
        $this->action = $action;
        $this->status = config('honey.recaptcha.status');
    }
    public function render(): View|Closure|string {
        return view('components.web.honey.recaptcha');
    }
}
