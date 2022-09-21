<?php

use Illuminate\Http\Request;
use App\Models\ProjectRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\api\v1\TestController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ProjectMessageController;
use App\Http\Controllers\ProjectRequestController;
use App\Http\Controllers\api\v1\auth\UserController;
use App\Http\Controllers\WorkspaceController;

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


    Route::prefix('subscription')->group(function () {
        Route::get('/', [SubscriptionController::class, 'index']);
        Route::get('/{id}', [SubscriptionController::class, 'show']);
        Route::post('/', [SubscriptionController::class, 'store'])->middleware(['permission:admin can manage subscription']);
        Route::put('/{id}', [SubscriptionController::class, 'update'])->middleware(['permission:admin can manage subscription']);
        Route::delete('/{id}', [SubscriptionController::class, 'destroy'])->middleware(['permission:admin can manage subscription']);
    });

    Route::prefix('cart')->group(function () {
        Route::post('new', [CartController::class, 'create']);
        Route::post('create', [CartController::class, 'store']);
        Route::get('/{reference}', [CartController::class, 'show']);
        Route::put('/{reference}', [CartController::class, 'update']);
        Route::delete('/item/{transaction}', [CartController::class, 'removeItem']);
        Route::post('/coupon/apply', [CouponController::class, 'verify']);
        Route::delete('/coupon/remove', [CouponController::class, 'remove']);
    });



    // Route::resource('requests', ProjectRequestController::class);
    // Route::resource('brands', BrandController::class);

    Route::prefix('dashboard')->middleware('check_workspace')->group(function () {
        Route::get('/', [DashboardController::class, 'home']);

        Route::prefix('brand')->middleware(['can:viewAny,App\Models\Brand' , 'check_subscription'])->group(function () {
            Route::get('/', [BrandController::class, 'index']);
            Route::put('/{id}', [BrandController::class, 'update']);
            Route::get('/{id}', [BrandController::class, 'show']);
            Route::post('/', [BrandController::class, 'store'])->middleware('can:create,App\Models\Brand');
            Route::delete('/{id}', [BrandController::class, 'destroy']);
        });

        Route::prefix('teams')->middleware(['can:teams-managment', 'check_subscription'])->group(function () {
            Route::get('/', [TeamController::class, 'index']);
            Route::post('/invite', [TeamController::class, 'invite']);
            Route::post('/invite/check/{id}', [TeamController::class, 'check']);
            Route::get('/{id}', [TeamController::class, 'show']);

            Route::delete('/{id}', [TeamController::class, 'delete']);
            Route::put('/{id}', [TeamController::class, 'update']);


            Route::post('/link/{id}', [TeamController::class, 'link']);

        });
        // ->middleware(['permission:client can managment subscription'])
        Route::prefix('plan')->group(function () {
            Route::get('/', [SubscriptionController::class, 'activeSub']);
            Route::get('/history', [SubscriptionController::class, 'list']);
            // Route::post('/withdrawal/bank', [AffiliateController::class, 'withdrawal']);
            // Route::get('/withdrawal', [AffiliateController::class, 'history']);
        });

        Route::prefix('companies')->group(function () {
            Route::get('/', [CompanyController::class, 'index']);
            Route::get('/{company}', [CompanyController::class, 'show']);
            Route::put('/{company}', [CompanyController::class, 'update']);
            Route::delete('/{company}', [CompanyController::class, 'destroy']);
        });

        Route::prefix('requests')->group(function () {
            Route::get('/', [ProjectRequestController::class, 'index']);
            Route::get('/{id}', [ProjectRequestController::class, 'show']);
            Route::post('/', [ProjectRequestController::class, 'store'])->middleware('check_subscription');
            Route::post('/upload_deliverables', [ProjectRequestController::class, 'uploadDeliverables']);
            Route::put('/{id}', [ProjectRequestController::class, 'update']);
            Route::delete('/{id}', [ProjectRequestController::class, 'destroy']);

            Route::prefix('messages')->group(function () {
                Route::get('/{project_id}', [ProjectMessageController::class, 'fetchMessages']);
                Route::post('/', [ProjectMessageController::class, 'sendMessage']);
            });
        });

    });

    Route::get('/auth/user/profile', [UserController::class, 'profile']);
    Route::put('/auth/user/profile', [UserController::class, 'updateProfile']);

    Route::prefix('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'home']);

        Route::prefix('promo')->middleware(['permission:admin can manage coupons'])->group(function () {
            Route::get('/', [CouponController::class, 'index']);
            Route::put('/{coupon}', [CouponController::class, 'update']);
            Route::get('/{coupon}', [CouponController::class, 'show']);
            Route::post('/create', [CouponController::class, 'store']);
            Route::delete('/{coupon}', [CouponController::class, 'destroy']);
        });

        Route::prefix('workspace')->middleware(['permission:admin can view workspace'])->group(function(){
            Route::get('/', [WorkspaceController::class, 'index']);
            Route::get('/company', [WorkspaceController::class, 'list']);
        });

        Route::prefix('portfolio')->group(function () {
            Route::get('/', [PortfolioController::class, 'index'])->withoutMiddleware('auth:sanctum');
            Route::post('/', [PortfolioController::class, 'store']);
            Route::delete('/{id}', [PortfolioController::class, 'destroy']);
        });
    });

    Route::prefix('affilate')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'affilate']);
        Route::post('/withdrawal/bank', [AffiliateController::class, 'withdrawal']);
        Route::get('/withdrawal', [AffiliateController::class, 'history']);
    });


});

Route::get('/test', [PaymentController::class, 'test']);
Route::post('/payment/webhook', [PaymentController::class, 'webhook']);

