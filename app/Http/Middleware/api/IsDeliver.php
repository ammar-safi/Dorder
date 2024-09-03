<?php

namespace App\Http\Middleware\api;

use App\Http\Traits\GeneralTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsDeliver
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::User()->type != "deliver") {
            return $this->Forbidden();
        }
        return $next($request);
    }
}
