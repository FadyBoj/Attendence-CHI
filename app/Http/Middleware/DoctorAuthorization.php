<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DoctorAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user;

        if($user->role !== 'doctor')
        throw new CustomException("Not authorized",401);

        return $next($request);
    }
}
