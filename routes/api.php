<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Controllers
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DoctorController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/qr',function () {
    return response()->json(["msg" => "This is a qr code test"]);
});


//Admin controller
Route::controller(AdminController::class)->group(function (){
    Route::post('/student','addStudent')->middleware('addStudentValidation');
    Route::post('/courses','studentCourses');

});


//Student Constroller


Route::middleware('customAuth')->group(function() {

    Route::controller(StudentController::class)->group(function (){
        Route::post('/take-attendence','takeAttendence')->middleware('attendenceValidation');
    
    });

});

Route::controller(StudentController::class)->group(function (){
    Route::post('/student-login','studentLogin')->middleware('studentLoginValidation');

});


//Doctors Controller

Route::middleware(['customAuth'])->group(function(){

    Route::controller(DoctorController::class)->group(function(){

        Route::post('/doctor/lecture','createLecture');
        Route::delete('doctor/lecture','endLecture');
        Route::get('/doctor/course/{id}','getAttendence');


    });

});