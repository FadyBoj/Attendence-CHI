<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentLoginValidation
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
            $request->validateWithBag("StudentLogin",[
                "college_id" => "required|exists:students",
                "password" => "required"
            ],[
                "college_id.required" => "The id field is required",
                "id.exists" => "Student id not found"
            ]);

            return $next($request);
        }
        catch(Exception $e)
        {
            throw new CustomException($e->getMessage(),500);
        }
    }
}
