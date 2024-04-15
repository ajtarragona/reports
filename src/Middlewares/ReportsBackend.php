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
        }else{
            // dd(session()->has('reports_login'));
            // dd(session()->has('reports_login'));
            if(session()->has('reports_login')){
                return $next($request);
            }else{
                return redirect()->route('tgn-reports.login');
            }
        }

    }
}