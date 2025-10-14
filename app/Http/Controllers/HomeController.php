<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\Configuration;
use App\Services\HomeService;
use App\Trait\HandleResponse;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    use HandleResponse;

    public function migrate(): JsonResponse
    {
        Artisan::call('migrate', [
            '--force' => true
        ]);

        return response()->json([
            'message' => 'Migration completed successfully.',
            'output' => Artisan::output(),
        ]);
    }

}
