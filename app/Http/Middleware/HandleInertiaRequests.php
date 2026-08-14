<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use App\Services\CapabilityService;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    public function __construct(
        private CapabilityService $capabilities,
        private MenuService $menus,
    ) {}

    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        $settings = Setting::where('autoload', true)->pluck('value', 'key');

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $user,
                'capabilities' => $user ? $this->capabilities->allFor($user) : [],
            ],
            'site' => [
                'title' => $settings['site_title'] ?? config('app.name'),
                'tagline' => $settings['tagline'] ?? '',
                'posts_per_page' => (int) ($settings['posts_per_page'] ?? 10),
            ],
            'menus' => $this->menus->all(),
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
