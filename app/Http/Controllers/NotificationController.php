<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Trait\HandleResponse;
use App\Mail\RequestACallMail;
use Illuminate\Support\Facades\Mail;


class NotificationController extends Controller
{
    use HandleResponse;
    public function fetchNotifications()
    {
        $notifications = Notification::where([
            'user_id'=> auth()->user()->id,
            'read'=> 'false'
        ])->get();

        return $this->successResponse($notifications , '', 200);
    }

    public function requestCall(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required',
            'mobile_no' => 'required',
        ]);

        try {
            Mail::to($request->email)->send(new RequestACallMail($request->full_name, $request->mobile_no));
        } catch (\Throwable $th) {
        }

        return $this->successResponse('' , 'Sent Successfully', 200);

    }

    public function requestDemo(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required',
            'mobile_no' => 'required',
            'email' => 'required',
            'hear_about_us' => 'required',
        ]);

        try {
            Mail::to($request->email)->send(new RequestACallMail($request->full_name, $request->mobile_no,$request->email,$request->hear_about_us,));
        } catch (\Throwable $th) {
        }

        return $this->successResponse('' , 'Sent Successfully', 200);

    }
}
