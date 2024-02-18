<?php

namespace App\Http\Controllers;
use App\Exceptions\CustomException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


// Models
use App\Models\Course;
use App\Models\Student;
use App\Models\CourseStudent;
use App\Models\Doctor;

class AdminController extends Controller
{
    public function addStudent(Request $request) 
    {
        $data = $request->all();

        foreach($data['courses'] as $course)
        {
            if(!Course::where('id',$course)->exists())
            throw new CustomException("Course with id of $course is not found",400);

        }

        $newStudent = Student::create([
            "college_id" => $data['college_id'],
            "name" => $data['name'],
            "department" => $data["department"],
            "password" => $data['password']
        ]);

        foreach($data['courses'] as $course)
        {
            CourseStudent::create([
                "student_id" => $newStudent->id,
                "course_id" => $course
            ]);
        }
 
    

        return response()->json(["msg" => "Successfully added Student"],200);
    }


    // public function studentCourses(Request $request) {

    //     $studentId = Student::where('college_id',$request->id)->first()->id;
    //     $courses = Student::find($studentId)->courses()->get();

    //     return response()->json(["Courses" => $courses]);
    // }

    

    //Add new Doctor

    public function addDoctor(Request $request) 
    {
        $data = $request->all();

        Doctor::create([
            "name" => $data['name'],
            "password" => Hash::make($data['password']),
        ]);

        return response()->json(["msg" => "Doctor added successfully"],200);
    }
}
