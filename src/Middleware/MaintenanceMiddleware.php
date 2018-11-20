<?php
namespace Piclou\Ikcms\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;

class MaintenanceMiddleware {

    public function __construct(Application $app, Request $request) {
        $this->app = $app;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
       if(setting("website.maintenance")){
           die("Le site est en maintenance.");
       }

        return $next($request);
    }

}