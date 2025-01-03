<?php

use App\Models\Cart;
use App\Models\CompanyUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

function uploadDocument($file, $path, $name = null)
{

    $destinationPath = 'images';
    $destinationPath = '/images'. $path;

     if (!File::isDirectory($destinationPath)) {
        File::makeDirectory($destinationPath, 0777, true, true);
    }

    if (is_null($name)) {
            $name = time() .'.'. $file->extension();
        } else {
            $name = $name;
        }


    $file->move(public_path($destinationPath),  $name);

    return  $destinationPath.'/'.$name;


    // $destination = storage_path('app/public/' . $path);
    // //  check if file storage path already exists. If No, create a new one
    // if (!File::isDirectory($destination)) {
    //     File::makeDirectory($destination, 0777, true, true);
    // }

    // if (is_null($name)) {
    //     $filename = time() . $file->extension();
    // } else {
    //     $filename = $name;
    // }

    // file_put_contents($destination . $filename, $file);

    // return  '/storage' . $path . $filename;
}

function generateReference(): string
{
    $ref = config('keys.payment');
    while (Cart::whereReference($ref)->exists()) {
        $ref = config('keys.payment');
    }
    return $ref;
}


// function getActiveWorkSpace()
// {
//     return Session::get('current_workspace') ?? false;
// }
function getActiveWorkSpace($userId)
{

    $company_user = CompanyUser::where([
        "user_id" => $userId,
        "active_status" => true
    ])->with("company")->first();


    return $company_user->company ?? false;
}

function setActiveWorkSpace($workspace, $userId, $change = false)
{
    if ($change) {
        //deactivate all workspaces
        CompanyUser::where([
            "user_id" => $userId,
        ])->update([
            "active_status" => false,
        ]);

        //activate current workspace
        CompanyUser::where([
            "user_id" => $userId,
            "company_id" => $workspace->id
        ])->update([
            "active_status" => true,
        ]);
    }

    $current = getActiveWorkSpace($userId);
    return $current;
}

