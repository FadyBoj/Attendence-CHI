<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;


//Models 
use App\Models\ActiveLecture;
use App\Models\Attendence;
use App\Models\Course;
use App\Models\SignedStudent;
use App\Models\Student;

class DoctorController extends Controller
{

    public function createLecture(Request $request)
    {
        $request->validateWithBag("createLecture",[
            "id" => "required|exists:courses",
        ],[
            "id.exists" => "Course id not found"
        ]);

        $doctor = $request->user;
        $data = $request->all();
        $uniqueId = (string)Str::uuid();

        if(ActiveLecture::where('id',$data['id'])->exists())
        {
            $activeLecture = ActiveLecture::find($data['id']);

            if(!now()->gt($activeLecture->expireDate))
            throw new CustomException("Lecture session already exist",400);
            
            $activeLecture->expireDate = now()->addMinutes(120);
            $activeLecture->uniqueId = $uniqueId;
            $activeLecture->save();
        }
        else
        {
            ActiveLecture::create([
                "id" => $data['id'],
                "doctor_id" => $doctor->id,
                "uniqueId" => $uniqueId,
                "expireDate" => now()->addMinutes(120)
            ]);
        }

        $qr = QrCode::size(400)->generate($data['id']."|$uniqueId");

        return response($qr);

    }

    //Get course attendence

    public function getAttendence(Request $request, int $id)
    {
    
        $attendence = Attendence::with(['student' => function ($query) {
            $query->select(['id','college_id', 'name','department']); // Include only specific attributes from the student table
        }])->where('course_id', $id)->get();

        if(count($attendence) === 0)
        throw new CustomException("No attendence for course with id of $id",400);

        return response()->json(["msg" => $attendence],200);
    }


    // End lecture manually
    public function endLecture(Request $request)
    {
        $request->validateWithBag('endLecture',[
            "id" => "required|exists:active_lectures"
        ],[
            "id.exists" => "Active lecture id not found"
        ]);

        $id = $request->id;

        ActiveLecture::where('id',$id)->delete();

        return response()->json(["msg" => "Lecture Ended successfully"],200);   
    }

    //Get specific course studetns
    public function courseStudents(Request $request, int $id) 
    {
       if(!Course::where('id',$id)->exists())
       throw new CustomException("Course id not found",400);

       $students = Course::find($id)->students()->get();

       return response()->json(["Students" => $students],200);

    }

    //Take student attendence manually
    public function takeAttendenceManually(Request $request)
    {

        $request->validateWithBag('manualAttendence',
        [
            "college_id" => "required|exists:students",
            "lecture_id" => "required|exists:active_lectures,id"
        ],[
            "college_id.exists" => "Student id not found",
            "lecture_id.exists" => "Lecture ended"
        ]);

        $data = $request->all();

        $activeLecture = ActiveLecture::find($data['lecture_id']);
        $student = Student::where('college_id',$request->college_id)->first();

        if(now()->gt($activeLecture->expireDate))
        {
            ActiveLecture::where('id',$data['lecture_id']);
            throw new CustomException("Lecture Ended");
        }

        //Check if student attendence already taken for this session
        $signedStudent = $activeLecture->students()->where('id',$student->id)->get();

        if(count($signedStudent) > 0)
        throw new CustomException("Student attendence already has been taken",400);


        $studentAttendence = Attendence::where('student_id',$student->id)
        ->where('course_id',$data['lecture_id'])
        ->first();

        if(Attendence::where('student_id',$student->id)
        ->where('course_id',$data['lecture_id'])
        ->exists())
        {
            $studentAttendence->count = ($studentAttendence->count + 1);
            $studentAttendence->save();
        }

        else
        {
            Attendence::create([
                "student_id" => $student->id,
                "course_id" => $data['lecture_id'],
                "count" => 1
            ]);
        }
        
        SignedStudent::create([
            "id" => $student->id,
            "activeLecture_id" => $data['lecture_id']
        ]);


        return response()->json(["msg" => "Student attendence taken successfully"],200);

    }
}
