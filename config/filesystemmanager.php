<?php

return [
    "storage" => [
        'size' => [
            "local" => env('FM_STORAGE_LOCAL_SIZE', "1GB"),
            "public" => env('FM_STORAGE_PUBLIC_SIZE', "1GB"),
            "s3" => env('FM_STORAGE_S3_SIZE', 0),
        ],
    ],
    /********************************
     *
     *  This is required to get authenticated user at StorageController
     *  to check if the user can view the file
     *
     */
    'auth' => [
        'user' => fn() => auth()->user()
    ],

    /********************************
     *
     * Set Avatar true to enable avatar into users
     *
     */
    "user" => [
        "avatar" => true,
        "width" => 512,
        "height" => 512,
        "quality" => 80,
        "generatorUrl" => "https://ui-avatars.com/api/?size=512&name="
    ]
];
