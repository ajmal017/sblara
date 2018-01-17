<?php

namespace App\Http\Middleware;

use Closure;

class CacheMiddleware
{
    protected $active = false;

    function __construct()
    {
        return $this->active = $this->pagesToCache();
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
        if(!$this->active)
        {
            return $next($request);
        }

       return $cache = app(\Barryvdh\HttpCache\Middleware\CacheRequests::class)->handle($request, function ($request) use ($next)
        {
            return $next($request);
        });
    }

    public function pagesToCache()
    {
        $route = \Request::route()->getName();
        /*always cache block*/
        if($route == "Ajax.load_block")
        {
            return true;
        }
        /*always cache block*/

        /*never cache auth user pages because one user will see another user information*/
        if(\Auth::guest())
        {
            return true;
        }
        /*never cache auth user pages*/
    }
}