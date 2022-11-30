<?php

namespace App\Http\Middleware;

use App\Services\MsGraphService;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    protected MsGraphService $msGraphService;

    public function __construct(Factory $auth, MsGraphService $msGraphService)
    {
        parent::__construct($auth);
        $this->msGraphService = $msGraphService;
    }

    public function handle($request, Closure $next, ...$guards)
    {
        $user = $this->msGraphService->getUser();

        if ($user) {
            Auth::setUser($user);
            return $next($request);
        }

        $this->unauthenticated($request, $guards);
    }
}
