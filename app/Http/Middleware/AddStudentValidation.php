<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Exceptions\CustomException;
use Exception;
use Illuminate\Support\Facades\Hash;

    

class AddStudentValidation
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
            $request->validateWithBag('studentValidation',[
                "college_id" => "required|unique:students|min_digits:8|numeric",   
                "name" => "required|min:4|string",
                "department" => "required|min:2|string",
                "courses" => "array|required"
            ],[
                "id.required" => "This id field is required",
            ]);

            $request->merge([
                "password" =>Hash::make($request->college_id)
            ]);

            

        }
        catch(Exception $e)
        {
            throw new CustomException($e->getMessage(),500);
        }

        return $next($request);

    }
}
