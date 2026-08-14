<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Show the public home page.
     */
    public function __invoke(Request $request): Response
    {
        $posts = Post::query()
            ->where('type', 'post')
            ->where('status', 'published')
            ->with('author:id,name')
            ->latest('published_at')
            ->take((int) Setting::get('posts_per_page', 10))
            ->get(['id', 'title', 'slug', 'excerpt', 'published_at', 'author_id']);

        return Inertia::render('Public/Home', [
            'posts' => $posts,
        ]);
    }
}
