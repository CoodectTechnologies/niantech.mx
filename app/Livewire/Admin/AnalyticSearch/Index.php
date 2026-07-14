<?php

namespace App\Livewire\Admin\AnalyticSearch;

use App\Models\AnalyticSearch;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $perPage = 20;
    public $search;
    protected $queryString = ['search'];
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['render'];
    public $dateStart;
    public $dateEnd;
    public $filter;

    public function mount(Request $request) {
        if ($request->rangeDateGrapich) {
            $rangeDateGrapich = explode(' - ', $request->rangeDateGrapich);
            $this->dateStart = $rangeDateGrapich[0];
            $this->dateEnd = $rangeDateGrapich[1];
        } else {
            $this->dateStart = Carbon::today()->subMonth()->format('Y-m-d');
            $this->dateEnd = Carbon::createFromDate(date('Y'), 12, 31)->format('Y-m-d');
        }
    }
    public function updatingSearch() {
        $this->resetPage();
    }
    public function render() {
        $analyticSearches = AnalyticSearch::query()->where('created_at', '>=', $this->dateStart)->where('created_at', '<=', $this->dateEnd)->orderBy('id', 'desc');
        $pieChartData = $this->getPieChartData();
        if ($this->search) {
            $analyticSearches = $analyticSearches->where('search', 'LIKE', "%{$this->search}%");
        }
        if ($this->filter == __('Negatives')) {
            $analyticSearches = $analyticSearches->where('founded', false);
        } elseif ($this->filter == __('Positives')) {
            $analyticSearches = $analyticSearches->where('founded', true);
        }

        $analyticSearches = $analyticSearches->paginate($this->perPage);

        return view('livewire.admin.analytic-search.index', compact('analyticSearches', 'pieChartData'));
    }
    public function getPieChartData() {
        $countPositives = AnalyticSearch::where('founded', true)->where('created_at', '>=', $this->dateStart)->where('created_at', '<=', $this->dateEnd)->count();
        $countNegatives = AnalyticSearch::where('founded', false)->where('created_at', '>=', $this->dateStart)->where('created_at', '<=', $this->dateEnd)->count();

        return [
            'labels' => [__('Positive searches'), __('Negative searches')],
            'series' => [$countPositives, $countNegatives],
            'colors' => ['#50cd89', '#f1416c'],
        ];
    }
    public function destroy(AnalyticSearch $analyticSearch) {
        try {
            $analyticSearch->delete();
            $this->dispatch('alert', 'success', __('Successful elimination'));
        } catch (Exception $e) {
            report($e);
            $this->dispatch('alert', 'error', $e->getMessage());
        }
    }
}
