<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Constants\FlashType;

class CheckRole {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public function handle(Request $request, Closure $next, string ...$roles) {
        if (!in_array(Auth::user()->user_type, $roles)) {
            return redirect()->route('welcome')->with(FlashType::ERROR, 'No tienes permisos para acceder a esta página');
        }
        
        return $next($request);
    }
}

