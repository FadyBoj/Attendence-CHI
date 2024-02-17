<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;


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



    Route::controller(StudentController::class)->group(function (){
        Route::post('/take-attendence','takeAttendence')->middleware('auth:api');
    
    });

Route::controller(StudentController::class)->group(function (){
    Route::post('/student-login','studentLogin')->middleware('studentLoginValidation');

});

