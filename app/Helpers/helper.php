<?php

use App\Models\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

function uploadDocument($file, $path, $name = null)
{
    $destination = storage_path('app/public/' . $path);
    //  check if file storage path already exists. If No, create a new one
    if (!File::isDirectory($destination)) {
        File::makeDirectory($destination, 0777, true, true);
    }

    if (is_null($name)) {
        $filename = time() . $file->extension();
    } else {
        $filename = $name;
    }

    file_put_contents($destination . $filename, $file);

    return  url('/') . '/storage' . $path . $filename;
}

function generateReference(): string
{
    $ref = config('keys.payment');
    while (Cart::whereReference($ref)->exists()) {
        $ref = config('keys.payment');
    }
    return $ref;
}


function getActiveWorkSpace()
{
    return Session::get('current_workspace') ?? false;
}

function setActiveWorkSpace($workspace, $change = false)
{
    if ($change) {
        Session::put('current_workspace', $workspace);
    }

    $current = getActiveWorkSpace();
    return $current;
}

