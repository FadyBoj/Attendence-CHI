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


            if(!Auth::guard('api')->check() || !$token)
            throw new CustomException("unauthenticated",401);

            $user = Auth::guard('api')->user();

            $request->merge(["user" => $user]);

             return $next($request);
        }
        catch(Exception $e)
        {
            throw new CustomException($e->getMessage(),500);
        }
    }
}
