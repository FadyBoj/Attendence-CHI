<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rules\Password;


class AddDoctorValidation
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
            $request->validateWithBag('addDoctor',[
                "name" => "required|unique:doctors|string|min:3",
                "password" => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->uncompromised()]
            ],[ 

            ]);

            return $next($request);
        }
        catch(Exception $e)
        {
            throw new CustomException($e->getMessage(),500);
        }
    }
}
