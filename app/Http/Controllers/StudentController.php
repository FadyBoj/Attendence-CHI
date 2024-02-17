<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class StudentController extends Controller
{
    public function studentLogin(Request $request) 
    {
        $credentials = $request->only("id","password");
 
        $student = Student::find($credentials['id']);
        $hashedPassword = $student->password;

        if (!hash::check($credentials['password'],$hashedPassword)) 
        throw new CustomException("Id and password are mismatched",400);

        $student->tokens()->delete();
        $token = $student->createToken('ApiToken')->accessToken;
        return response()->json(["token" => $token],200);
    } 

    public function takeAttendence(Request $request)
    {
        return response()->json(["msg" => "Importnant data"],200);
    }
}
