<?php

namespace App\Http\Middleware;

use Closure;

class Maintain
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

//        if($request->headers->get('edition') < config('edition')){
//            return redirect()->to(url('edition'));
//        }
//
//        if(config('maintain') != 'up'){
//            return redirect()->to(url('maintain'));
//        }

        return $next($request);
    }
}
