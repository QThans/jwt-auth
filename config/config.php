<?php


return [
    'secret'            => env('JWT_SECRET'),
    //JWT time to live
    'ttl'               => env('JWT_TTL', 60),
    //Refresh time to live
    'refresh_ttl'       => env('JWT_REFRESH_TTL', 20160),
    //JWT hashing algorithm
    'algo'              => env('JWT_ALGO', 'HS256'),

    'blacklist_storage' => thans\jwt\provider\storage\Tp5::class,
];
