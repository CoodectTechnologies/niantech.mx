<?php

namespace App\Livewire\Admin\Dashboard\EmailWeb;

use App\Models\EmailWeb;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $year;

    public function mount() {
        $this->year = date('Y');
    }
    public function render() {
        $emailWebs = EmailWeb::orderBy('id', 'desc')->get();
        $emailWebsRecent = $emailWebs->take(10);
        $emailWebsYes = $emailWebs->where('conversion', 'Si');
        $emailWebsNo = $emailWebs->where('conversion', 'No');
        $emailWebsWatting = $emailWebs->where('conversion', 'En espera');

        $grapihEmailsData = $this->grapihEmailsData;
        $grapihEmailsByConversionData = $this->grapihEmailsByConversionData;

        return view('livewire.admin.dashboard.email-web.index', compact(
            'emailWebsRecent',
            'emailWebsYes',
            'emailWebsNo',
            'emailWebsWatting',
            'grapihEmailsData',
            'grapihEmailsByConversionData'
        ));
    }
    public function getEmailWebTotalProperty() {
        return EmailWeb::query()->count('id');
    }
    public function getEmailWebTotalTodayProperty() {
        return EmailWeb::query()->whereDate('created_at', today())->count('id');
    }
    public function getEmailWebTotalMonthProperty() {
        return EmailWeb::query()->whereMonth('created_at', date('m'))->whereYear('created_at', $this->year)->count('id');
    }
    public function getEmailWebTotalYearProperty() {
        return EmailWeb::query()->whereYear('created_at', $this->year)->count('id');
    }
    public function getGrapihEmailsDataProperty() {
        $emailWebs = EmailWeb::select(
            DB::raw('DATE_FORMAT(created_at, "%m-%Y") AS month2'),
            DB::raw('DATE_FORMAT(created_at, "%b-%Y") AS month'),
            DB::raw('COUNT(id) AS countEmails')
        )
            ->whereYear('created_at', $this->year)
            ->orderBy('month2')
            ->groupBy('month', 'month2')
            ->get();

        $dates = [];
        $totals = [];

        foreach ($emailWebs as $emailWeb) {
            $dates[] = $emailWeb->month;
            $totals[] = (int) $emailWeb->countEmails;
        }

        return [
            'dates' => $dates,
            'totals' => $totals,
        ];
    }
    public function getGrapihEmailsByConversionDataProperty() {
        $emailWebs = EmailWeb::select(
            DB::raw('conversion'),
            DB::raw('COUNT(id) AS countIds')
        )
            ->whereYear('created_at', $this->year)
            ->groupBy('conversion')
            ->get();

        $labels = [];
        $values = [];
        $colors = [];

        foreach ($emailWebs as $emailWeb) {
            $labels[] = $emailWeb->conversion;
            $values[] = (int) $emailWeb->countIds;

            if ($emailWeb->conversion == 'Si') {
                $colors[] = '#50cd89';
            } elseif ($emailWeb->conversion == 'En espera') {
                $colors[] = '#ffc700';
            } elseif ($emailWeb->conversion == 'No') {
                $colors[] = '#f1416c';
            } else {
                $colors[] = '#918d8d';
            }
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'colors' => $colors,
        ];
    }
}
