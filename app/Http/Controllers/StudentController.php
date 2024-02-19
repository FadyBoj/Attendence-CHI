<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use function Laravel\Prompts\error;

//Models 
use App\Models\ActiveLecture;
use App\Models\Student;
use App\Models\SignedStudent;
use App\Models\Attendence;




class StudentController extends Controller
{
    /**
 * @OA\Post(
 *     tags={"Student"},
 *     path="/api/student/login",
 *     summary="Login a user",
 *      @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="college_id",
 *                     type="number"
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string"
 *                 ),
 *                 example={"college_id": 2021030043,  "password": "AKDhsa92yd"}
 *             )
 *         )
 *     ),
 *      @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *            
 *             @OA\Examples(example="result", value={"token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5YjVjNTlmNy00ODA3LTQ0M2EtODM4Mi0yM2I5NmM5ZTEyOWYiLCJqdGkiOiI5OTUxYWYw"}, summary="An result object."),
 *         )
 *     ),
 *      @OA\Response(
 *         response=400,
 *         description="BAD REQUEST",
 *         @OA\JsonContent(
 *            
 *             @OA\Examples(example="result", value={"msg": "id and password are mismatched"}, summary="An result object."),
 *         )
 *     )
 * )
 */


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
    /**
    * @OA\SecurityScheme(
    *     securityScheme="bearerAuth",
    *     type="http",
    *     scheme="bearer",
    *    bearerFormat="JWT",
    *     description="JWT Token Authentication. Please insert the token in the 'Authorization' header as 'Bearer {token}'.",
    * )
    */

    /**
 * @OA\Post(
 *     tags={"Student"},
 *     path="/api/student/take-attendence",
 *     summary="Take student attendence (required id of the course and the uniqueId assigned to the qr code)",
 *      
 *     security={{"bearerAuth": {}}},
 * 
 *      @OA\Parameter(
 *          name="Authorization",
 *           in="header",
 *           description="Bearer Token",
 *           required=true,
 *              
 *           @OA\Schema(
 *              type="string"
 *           )
 *      ),
 * 
 *      @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="id",
 *                     type="number"
 *                 ),
 *                 @OA\Property(
 *                     property="uniqueId",
 *                     type="string"
 *                 ),
 *                 example={"id": 4,  "uniqueId": "c5b14707-2e6f-4f58-a3a4-db7f8ff18f49"}
 *             )
 *         )
 *     ),
 *      @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *            
 *             @OA\Examples(example="result", value={"msg": "attendence taken"}, summary="An result object."),
 *         )
 *     ),
 *      @OA\Response(
 *         response=400,
 *         description="Id student attendence already taken for the day",
 *         @OA\JsonContent(
 *            
 *             @OA\Examples(example="result", value={"msg": "Attendence already taken"}, summary="An result object."),
 *         )
 *     )
 * )
 */

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

    //Reset password

    /**
    * @OA\SecurityScheme(
    *     securityScheme="bearerAuth",
    *     type="http",
    *     scheme="bearer",
    *       bearerFormat="JWT",
    *     description="JWT Token Authentication. Please insert the token in the 'Authorization' header as 'Bearer {token}'.",
    * )
 * @OA\Post(
 *     tags={"Student"},
 *     path="/api/student/reset-password",
 *      
 *     summary="Reset student password",
 *     security={{"bearerAuth": {}}},
 *      @OA\Parameter(
 *          name="Authorization",
 *           in="header",
 *           description="Bearer Token",
 *           required=true,
 *              
 *           @OA\Schema(
 *              type="string"
 *           )
 *           ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="old_password",
 *                     type="string",
 *                     example="2021030043"
 *                 ),
 *                 @OA\Property(
 *                     property="new_password",
 *                     type="string",
 *                     example="daswdaFs$3"
 *                 ),
 *                 @OA\Property(
 *                     property="new_password_confirmation",
 *                     type="string",
 *                     example="daswdaFs$3"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             @OA\Examples(example="result", value={"msg": "Password changed Successfully"}, summary="An result object.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Wrong password",
 *         @OA\JsonContent(
 *             @OA\Examples(example="result", value={"msg": "Wrong password"}, summary="An result object.")
 *         )
 *     )
 * )
 */


    public function resetPassword(Request $request)
    {
        $request->validateWithBag('resetPassword',[
            "old_password" => "required",
            "new_password" => ['required', 'confirmed', Password::min(8)
            ->letters()
            ->mixedCase()
            ->numbers()
            ->uncompromised()],
        ],[

        ]);

        $user = $request->user;
        $hashedPassword = $user->password;
        $credentials = $request->all();

        if (!hash::check($credentials['old_password'],$hashedPassword)) 
        throw new CustomException("Wrong password",400);

        $newHasedPassword = Hash::make($credentials['new_password']);
        $user->password = $newHasedPassword;
        $user->save();


        return response()->json(["msg" => "Password changed Successfully"],200);
    }
    
}
