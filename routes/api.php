<?php

use App\Http\Controllers\api\v1\auth\UserController;
use App\Http\Controllers\api\v1\TestController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ProjectRequestController;
use App\Http\Controllers\UserSubcriptionController;
use App\Models\ProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/', [TestController::class, 'index']);


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::post('/', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    Route::prefix('requests')->group(function () {
        Route::get('/', [ProjectRequestController::class, 'index']);
        Route::get('/{id}', [ProjectRequestController::class, 'show']);
        Route::post('/', [ProjectRequestController::class, 'store']);
        Route::put('/{id}', [ProjectRequestController::class, 'update']);
        Route::delete('/{id}', [ProjectRequestController::class, 'destroy']);
    });

    Route::prefix('brand')->group(function () {
        Route::get('/', [BrandController::class, 'index']);
        Route::get('/{id}', [BrandController::class, 'show']);
        Route::post('/', [BrandController::class, 'store']);
        Route::put('/{id}', [BrandController::class, 'update']);
        Route::delete('/{id}', [BrandController::class, 'destroy']);
    });

    Route::prefix('user_subscription')->group(function () {
        Route::post('/', [UserSubcriptionController::class, 'store']);

    });

    // Route::resource('requests', ProjectRequestController::class);
    // Route::resource('brands', BrandController::class);
});
