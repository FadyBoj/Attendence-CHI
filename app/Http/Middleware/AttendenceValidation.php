<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AttendenceValidation
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
            $request->validateWithBag('attendence',[
                "id" => "required|exists:active_lectures",
                "uniqueId" => "required"
            ],[
                "id.exists" => "Invalid QR code or lecture already ended !"
            ]);

            return $next($request);
        }
        catch(Exception $e)
        {
            throw new CustomException($e->getMessage(),400);
        }
    }
}
