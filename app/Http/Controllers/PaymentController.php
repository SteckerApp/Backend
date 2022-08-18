<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function webhook(Request $request)
    {
        Log::info($request->all());
        dd($request->all());
    }

    public function test(Request $request)
    {
        dd(
    );
        // $sub = subscription::find(1);
        // tap($sub, function ($collection) {
        //     $collection->update([
        //         'default' => true
        //     ]);
        // });

        // dd($sub);
    }
}
