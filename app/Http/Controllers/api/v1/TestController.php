<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * check if the system is running
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()
            ->json(['message' => 'Stecker API Server']);
    }
}
