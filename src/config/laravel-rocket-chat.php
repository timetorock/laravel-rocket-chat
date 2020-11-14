<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Rocket Chat instance URL
    |--------------------------------------------------------------------------
    |
    */

    'instance' => env('RC_INSTANCE', 'rocketchat:3000'),

    /*
    |--------------------------------------------------------------------------
    | Rest api root
    |--------------------------------------------------------------------------
    |
    */

    'api_root' => env('RC_API_ROOT', '/api/v1/'),

    /*
     * Admin user_id/token, otherwise admin username/password used.
     * In case of username/password, new token generated with each request
     */

    'admin_id' => env('RC_ADMIN_ID', ''),
    'admin_token'   => env('RC_ADMIN_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Admin username
    |--------------------------------------------------------------------------
    |
    */

    'admin_username' => env('RC_ADMIN_USERNAME', 'admin'),

    /*
    |--------------------------------------------------------------------------
    | Admin password
    |--------------------------------------------------------------------------
    |
    */

    'admin_password' => env('RC_ADMIN_PASS', ''),
];
