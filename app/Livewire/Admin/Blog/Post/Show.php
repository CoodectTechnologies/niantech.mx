<?php

namespace App\Livewire\Admin\Blog\Post;

use App\Models\BlogPost;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Show extends Component
{
    public $post;

    public function mount(BlogPost $post) {
        $this->post = $post;
        $this->post->load(['blogCategories.blogPosts', 'blogTags.blogPosts']);
    }
    public function render() {
        $recentPosts = BlogPost::orderBy('id', 'desc')->take(5)->get();
        $graphicViewsData = $this->getGraphicViewsData();

        return view('livewire.admin.blog.post.show', compact('recentPosts', 'graphicViewsData'));
    }
    public function getGraphicViewsData() {
        $dates = [];
        $totals = [];

        $views = $this->post->views()->select(
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
