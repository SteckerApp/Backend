<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function fetchNotifications()
    {
        $notifications = Notification::where([
            'user_id'=> auth()->user()->id,
            'read'=> 'false'
        ])->get();

        return $this->successResponse($notifications , '', 200);
    }
}
