<?php

namespace App\Http\Controllers\Web\Blog;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request) {
        $banner = Banner::getCache()?->filter(fn ($banner) => $banner->moduleWeb->name === 'Blog')?->first();
        $query = BlogPost::query()->with(['blogTags', 'blogCategories', 'user', 'image']);

        // Filtrar por categoría
        if ($request->category) {
            $query->whereHas('blogCategories', function ($q) use ($request) {
                $q->where('slug', 'LIKE', '%'.$request->category.'%');
            });
        }
        // Filtrar por tag
        if ($request->tag) {
            $query->whereHas('blogTags', function ($q) use ($request) {
                $q->where('slug', 'LIKE', '%'.$request->tag.'%');
            });
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate();

        return view('web.blog.index', compact('posts', 'banner'));
    }
    public function show(BlogPost $post) {
        $post->load('blogTags', 'blogCategories', 'user', 'comments.user');
        views($post)->cooldown(now()->addHours(1))->record();
        // Posts recientes (excepto el actual)
        $recentPosts = BlogPost::where('id', '!=', $post->id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('web.blog.show', compact('post', 'recentPosts'));
    }
}
