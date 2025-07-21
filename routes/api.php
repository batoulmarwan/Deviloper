<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RequestController;
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
Route::post('/user_register',[AuthController::class,'user_register']);
Route::post('/user_login',[AuthController::class,'user_login']);
Route::post('/admin_login',[AuthController::class,'admin_login']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    
    return $request->user();
});
Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::post('/user_logout', [AuthController::class, 'user_logout']);
    Route::delete('/delete-account', [AuthController::class, 'delete_account']);
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/cvs', [ProjectController::class, 'indexCv']);
    Route::post('/cvs', [ProjectController::class, 'storeCv']);
    Route::post('/profileView', [AuthController::class, 'show']);
    Route::post('/profile', [AuthController::class, 'update']);
    Route::post('/attendance', [AttendanceController::class, 'Attendence']);
    Route::get('/attendance/history', [AttendanceController::class, 'history']);
    Route::get('/my-requests', [RequestController::class, 'myRequests']);
});
Route::prefix('admin')->middleware('auth:sanctum')->group(function () {
    Route::post('/admin_logout', [AuthController::class, 'admin_logout']);
    Route::post('/company', [AttendanceController::class, 'createCompany']);
    Route::post('/leave-requests', [RequestController::class, 'storeLeaveRequest']);
    Route::post('/wfh-requests', [RequestController::class, 'storeWfhRequest']);
});
Route::prefix('user')->group(function () {
    Route::post('password/email', [AuthController::class, 'ForgetPassword']);
    Route::post('password/code/check', [AuthController::class, 'CodeCheck']);
    Route::post('password/reset', [AuthController::class, 'ResetPassword']);
});
Route::prefix('admin')->group(function () {
    Route::post('password/email', [AuthController::class, 'ForgetPassword']);
    Route::post('password/code/check', [AuthController::class, 'CodeCheck']);
    Route::post('password/reset', [AuthController::class, 'ResetPassword']);
});
