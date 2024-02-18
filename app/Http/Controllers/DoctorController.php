<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

//Models 
use App\Models\ActiveLecture;

class DoctorController extends Controller
{
    public function createLecture(Request $request)
    {
        $request->validateWithBag("createLecture",[
            "id" => "required|exists:courses",
            "minutes" => "numeric|required|min:1|max:150"
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
            
            $activeLecture->expireDate = now()->addMinutes($data['minutes']);
            $activeLecture->uniqueId = $uniqueId;
            $activeLecture->save();
        }
        else
        {
            ActiveLecture::create([
                "id" => $data['id'],
                "doctor_id" => $doctor->id,
                "uniqueId" => $uniqueId,
                "expireDate" => now()->addMinutes($data['minutes'])
            ]);
        }
    

        $qr = QrCode::size(400)->generate($data['id']."|$uniqueId");

       
        return response($qr);

    }
}
