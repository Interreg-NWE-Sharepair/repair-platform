<?php

namespace App\Http\Middleware;

use App\Models\ActivitySector;
use App\Models\DeviceType;
use App\Models\Location;
use App\Models\OrganisationType;
use App\Scopes\IsVisibleScope;
use Closure;
use Illuminate\Http\Request;

class IsVisibleRequired
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Location::addGlobalScope(new IsVisibleScope());
        OrganisationType::addGlobalScope(new IsVisibleScope());
        DeviceType::addGlobalScope(new IsVisibleScope());
        ActivitySector::addGlobalScope(new IsVisibleScope());

        return $next($request);
    }
}
