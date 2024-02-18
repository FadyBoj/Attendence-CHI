<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\error;

//Models 
use App\Models\ActiveLecture;
use App\Models\Student;
use App\Models\SignedStudent;
use App\Models\Attendence;




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

    //Attendence

    public function takeAttendence(Request $request)
    {
        $user = $request->user;
        $data = $request->all();
        $activeLecture = ActiveLecture::find($data['id']);

        if(now()->gt($activeLecture->expireDate))
        {
            ActiveLecture::where('id',$data['id'])->delete();
            throw new CustomException("Lecture Ended",400);
        }

        $signedStudent = $activeLecture->students()->where('id',$user->id)->get();

        if($data['uniqueId'] != $activeLecture->uniqueId)
        throw new CustomException("Invalid QR code",400);

        if(count($signedStudent) > 0)
        throw new CustomException("Your attendence already has been taken",400);

       

        $studentAttendence = Attendence::where('student_id',$user->id)
        ->where('course_id',$data['id'])
        ->first();


        if(Attendence::where('student_id',$user->id)
        ->where('course_id',$data['id'])
        ->exists())
        {
            $studentAttendence->count = ($studentAttendence->count + 1);
            $studentAttendence->save();
        }

        else
        {
            Attendence::create([
                "student_id" => $user->id,
                "course_id" => $data['id'],
                "count" => 1
            ]);
        }
        
        SignedStudent::create([
            "id" => $user->id,
            "activeLecture_id" => $data['id']
        ]);

        return response()->json(["msg" => "Attendence Taken"],200);
    }
}
