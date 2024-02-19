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


//Admin controller

Route::controller(AdminController::class)->group(function (){
    Route::post('admin/login','adminLogin')->middleware('adminLoginValidation');
});
Route::middleware(['adminAuth'])->group(function(){

Route::controller(AdminController::class)->group(function (){
    Route::post('/admin/student','addStudent')->middleware('addStudentValidation');
    Route::post('/admin/doctor','addDoctor')->middleware('addDoctorValidation');
    Route::post('/admin/add-admin','addAdmin')->middleware('addAdminValidation');
    Route::post('/courses','studentCourses');

});

});

//Student Constroller


Route::middleware('customAuth')->group(function() {

    Route::controller(StudentController::class)->group(function (){
        Route::post('/student/take-attendence','takeAttendence')->middleware('attendenceValidation');
        Route::post('/student/reset-password','resetPassword');
    
    });

});

Route::controller(StudentController::class)->group(function (){
    Route::post('/student/login','studentLogin')->middleware('studentLoginValidation');

});


//Doctors Controller

Route::controller(DoctorController::class)->group(function(){
    Route::post('/doctor/login','doctorLogin')->middleware('doctorLoginValidation');
});

Route::middleware(['doctorAuth'])->group(function(){

    Route::controller(DoctorController::class)->group(function(){

        Route::post('/doctor/lecture','createLecture');
        Route::delete('doctor/lecture','endLecture');
        Route::get('/doctor/course/{id}','getAttendence');
        Route::get('/doctor/course/{id}/students','courseStudents');
        Route::post('/doctor/attendence','takeAttendenceManually');


    });

});