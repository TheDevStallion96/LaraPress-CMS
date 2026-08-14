<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index(Request $request): Response
    {
        $stats = [
            'posts' => [
                'total' => Post::count(),
                'published' => Post::where('status', 'published')->count(),
                'draft' => Post::where('status', 'draft')->count(),
                'pending' => Post::where('status', 'pending')->count(),
                'future' => Post::where('status', 'future')->count(),
                'trash' => Post::onlyTrashed()->count(),
            ],
            'pages' => Post::where('type', 'page')->count(),
            'comments' => [
                'total' => Comment::count(),
                'pending' => Comment::where('status', 'pending')->count(),
            ],
            'users' => User::count(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentComments' => Comment::query()
                ->with('post:id,title,slug')
                ->latest()
                ->take(5)
                ->get(),
            'recentPosts' => Post::query()
                ->with('author:id,name')
                ->latest()
                ->take(5)
                ->get(),
            'versions' => [
                'laravel' => app()->version(),
                'php' => PHP_VERSION,
                'cms' => '0.1.0',
            ],
        ]);
    }
}
