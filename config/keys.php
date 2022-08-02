<?php

use Illuminate\Support\Str;

return [
    'verification' => [
        'code' => random_int(10000000, 99999999)
    ],
    'password' => [
        'reset' => Str::random(15)
    ],
]
?>