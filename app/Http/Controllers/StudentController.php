<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\error;

class StudentController extends Controller
{
    public function studentLogin(Request $request) 
    {
        $credentials = $request->only("college_id","password");
 
        $studentId = Student::where('college_id',$credentials['college_id'])->first()->id;
        $student = Student::find($studentId);
        $hashedPassword = $student->password;

        if (!hash::check($credentials['password'],$hashedPassword)) 
        throw new CustomException("Id and password are mismatched",400);

        $student->tokens()->delete();
        $token = $student->createToken('ApiToken')->accessToken;
        return response()->json(["token" => $token],200);
    } 

    public function takeAttendence(Request $request)
    {
        $user = $request->user;
        $sessionId = $request->sessionId;

        $allSessions = session()->all();
        error_log(json_encode($allSessions));

        if(!$request->session()->has($sessionId))
        throw new CustomException("Invalid QR or lecure already ended",400);

        if(now()->gt(session()->get($sessionId)['expireDate']))
        throw new CustomException("lecure already ended",400);

        

        // return response()->json(["msg" => "Importnant data"],200);
    }
}
