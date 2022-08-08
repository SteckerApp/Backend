<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\api\v1\auth\UserController;


/*
|--------------------------------------------------------------------------
| Auth API Routes
|--------------------------------------------------------------------------
|
*/


/*
|--------------------------------------------------------------------------
| Register Routes
|--------------------------------------------------------------------------
|
*/
Route::post('/register', [UserController::class, 'register']);


/*
|--------------------------------------------------------------------------
| Password Reset Routes
|--------------------------------------------------------------------------
|
*/
Route::post('/password/reset', [UserController::class, 'passwordReset']);
Route::post('/resend/password/request', [UserController::class, 'resendPasswordReset']);
Route::put('/password', [UserController::class, 'changePassword']);

/*
|--------------------------------------------------------------------------
| Login Routes
|--------------------------------------------------------------------------
|
*/

Route::post('/login', [UserController::class, 'login']);


Route::middleware('auth:sanctum')->group(function(){

    /*
    |--------------------------------------------------------------------------
    | Email verification Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::post('/resend/verification', [UserController::class, 'resendVerification']);
    Route::post('/verify/email', [UserController::class, 'verifyEmail']);

    /*
    |--------------------------------------------------------------------------
    | Logout Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::delete('/logout', [UserController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | Add Company Routes
    |--------------------------------------------------------------------------
    |
    */
    Route::post('/company', [CompanyController::class, 'store']);

});
