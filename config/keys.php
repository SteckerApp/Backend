<?php

use Illuminate\Support\Str;

return [
    'verification' => [
        'code' => random_int(10000000, 99999999)
    ],
    'password' => [
        'reset' => Str::random(15)
    ],
    'payment'=> 'TRAN' .random_int(1000000000000, 9999999999999) ,
]
?>