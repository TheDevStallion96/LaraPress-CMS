<?php

namespace App\Http\Middleware;

use App\Services\CapabilityService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAccessMiddleware
{
    public function __construct(private CapabilityService $capabilities) {}

    /**
     * Require an authenticated user with the `read` capability.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user === null) {
            return redirect()->guest(route('login'));
        }

        if (! $this->capabilities->can('read', $user)) {
            abort(403);
        }

        return $next($request);
    }
}
