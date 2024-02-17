<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        try
        {
            $token = $request->cookie("accessToken") ?: $request->bearerToken();
            $request->headers->set("Authorization","Bearer " . $token);

            if(!$token)
            throw new CustomException("Token must be provided",401);

            if(!Auth::guard('api')->check())
            throw new CustomException("unauthenticated",401);


             return $next($request);
        }
        catch(Exception $e)
        {
            throw new CustomException($e->getMessage(),500);
        }
    }
}
