<?php

namespace App\Livewire\Admin\Service;

use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Show extends Component
{
    public $service;

    public function mount(Service $service) {
        $this->service = $service;
        $this->service->load(['projects']);
    }
    public function render() {
        $recentServices = Service::orderBy('id', 'desc')->take(5)->get();
        $graphicViewsData = $this->getGraphicViewsData();

        return view('livewire.admin.service.show', compact('recentServices', 'graphicViewsData'));
    }
    public function getGraphicViewsData() {
        $dates = [];
        $totals = [];

        $views = $this->service->views()->select(
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
