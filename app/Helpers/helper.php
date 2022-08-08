<?php
use Illuminate\Support\Facades\File;

function uploadDocument($file, $path, $name = null)
{
    $destination = storage_path('app/public/' . $path);
    //  check if file storage path already exists. If No, create a new one
    if (!File::isDirectory($destination)) {
        File::makeDirectory($destination, 0777, true, true);
    }

    if(is_null($name)){
        $filename = time() .$file->extension();
    }else{
        $filename = $name;
    }

    file_put_contents($destination.$filename, $file);

    return  url('/').'/storage'.$path.$filename;
}
