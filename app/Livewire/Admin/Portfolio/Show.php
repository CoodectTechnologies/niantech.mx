<?php

namespace App\Livewire\Admin\Portfolio;

use App\Models\Portfolio;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Show extends Component
{
    public $project;

    public function mount(Portfolio $project) {
        $this->project = $project;
        $this->project->load(['service']);
    }
    public function render() {
        $recentProjects = Portfolio::orderBy('id', 'desc')->take(5)->get();
        $graphicViewsData = $this->getGraphicViewsData();

        return view('livewire.admin.portfolio.show', compact('recentProjects', 'graphicViewsData'));
    }
    public function getGraphicViewsData() {
        $dates = [];
        $totals = [];

        $views = $this->project->views()->select(
            DB::raw('DATE_FORMAT(viewed_at, "%m-%Y") AS month2'),
            DB::raw('DATE_FORMAT(viewed_at, "%b-%Y") AS month'),
            DB::raw('COUNT(id) AS views')
        )
            ->whereYear('viewed_at', date('Y'))
            ->orderBy('month2')
            ->groupBy('month', 'month2')
            ->get();

        foreach ($views as $view) {
            $dates[] = $view->month;
            $totals[] = (int) $view->views;
        }

        return [
            'dates' => $dates,
            'totals' => $totals,
        ];
    }
}
