<?php

namespace App\Livewire\Admin\Dashboard\Blog;

use App\Models\BlogPost;
use App\Models\Comment;
use Carbon\Carbon;
use CyrildeWit\EloquentViewable\Support\Period;
use CyrildeWit\EloquentViewable\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $dateStart;
    public $dateEnd;

    public function mount(Request $request) {
        if ($request->rangeDateGrapich) {
            $rangeDateGrapich = explode(' - ', $request->rangeDateGrapich);
            $this->dateStart = $rangeDateGrapich[0];
            $this->dateEnd = $rangeDateGrapich[1];
        } else {
            $this->dateStart = Carbon::createFromDate((date('Y')), 01, 01)->format('y-m-d');
            $this->dateEnd = Carbon::createFromDate(date('Y'), 12, 31)->format('y-m-d');
        }
    }
    public function render() {
        $graphicPostsViewsData = $this->getGraphicPostsViewsData();

        return view('livewire.admin.dashboard.blog.index', compact('graphicPostsViewsData'));
    }
    public function getViewsTotalProperty() {
        return views(BlogPost::class)->period(Period::create($this->dateStart), $this->dateEnd)->count();
    }
    public function getGraphicPostsViewsData() {
        $blogViews = View::select(
            DB::raw('DATE_FORMAT(viewed_at, "%m-%Y") AS month2'),
            DB::raw('DATE_FORMAT(viewed_at, "%b-%Y") AS month'),
            DB::raw('COUNT(id) AS views')
        )
            ->where('viewable_type', BlogPost::class)
            ->whereDate('viewed_at', '>=', $this->dateStart)
            ->whereDate('viewed_at', '<=', $this->dateEnd)
            ->orderBy('month2')
            ->groupBy('month', 'month2')
            ->get();

        $dates = [];
        $totals = [];
        foreach ($blogViews as $blogView) {
            $dates[] = $blogView->month;
            $totals[] = (int) $blogView->views;
        }

        return [
            'dates' => $dates,
            'totals' => $totals,
        ];
    }
    public function getBlogPostsPublishedProperty() {
        return BlogPost::where('status', 'Publicado')
            ->where('created_at', '>=', $this->dateStart)->where('created_at', '<=', $this->dateEnd)
            ->orderBy('id', 'desc')->get();
    }
    public function getBlogPostsNoPublishedProperty() {
        return BlogPost::where('status', 'Borrador')
            ->where('created_at', '>=', $this->dateStart)->where('created_at', '<=', $this->dateEnd)
            ->orderBy('id', 'desc')->get();
    }
    public function getCommentsApprovedProperty() {
        return Comment::where('commentable_type', BlogPost::class)
            ->where('created_at', '>=', $this->dateStart)->where('created_at', '<=', $this->dateEnd)
            ->where('approved', true)->get();
    }
    public function getCommentsNoApprovedProperty() {
        return Comment::where('commentable_type', BlogPost::class)
            ->where('created_at', '>=', $this->dateStart)->where('created_at', '<=', $this->dateEnd)
            ->where('approved', false)->get();
    }
}
