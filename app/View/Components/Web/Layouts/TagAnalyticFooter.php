<?php

namespace App\View\Components\Web\Layouts;

use App\Models\TagAnalytic;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TagAnalyticFooter extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|\Closure|string
     */
    public function render() {
        $tagAnalytic = TagAnalytic::getCache();

        return view('components.web.layouts.tag-analytic-footer', compact('tagAnalytic'));
    }
}
