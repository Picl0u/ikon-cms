<?php

namespace Piclou\Ikcms\Middleware;

use Closure;

class AdministrationAccess
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
        if(auth()->check()) {
            if(array_key_exists(auth()->user()->role, config('ikcms.adminRoles'))) {
                return $next($request);
            }
        }
        session()->flash('error',__('ikcms::admin.permission_error'));
        return redirect()->route('ikcms.admin.login');
    }
}
