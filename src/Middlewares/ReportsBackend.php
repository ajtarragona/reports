<?php

namespace Ajtarragona\Reports\Middlewares;

use Closure;

class ReportsBackend
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
    	if (!config("reports.backend")) {
    		 abort(403, "Oops! Reports backend is disabled");
        }

        return $next($request);
    }
}