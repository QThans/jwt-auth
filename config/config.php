<?php


return [
    'secret'      => env('JWT_SECRET'),
    //Asymmetric key
    'public_key'  => env('JWT_PUBLIC_KEY'),
    'private_key' => env('JWT_PRIVATE_KEY'),
    'password'    => env('JWT_PASSWORD'),
    //JWT time to live
    'ttl'         => env('JWT_TTL', 60),
    //Refresh time to live
    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160),
    //JWT hashing algorithm
    'algo'        => env('JWT_ALGO', 'HS256'),
    //token获取方式，数组靠前值优先
    'token_mode'    => ['header', 'cookie', 'param'],
    'blacklist_storage' => thans\jwt\provider\storage\Tp5::class,
];
