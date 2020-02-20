<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Frontend Url
    |--------------------------------------------------------------------------
    |
    | This value is used to construct Urls that direct traffic to the front end.
    | An example being the email verification route used when a user signs up
    | for a new account. This allows for the proper direction of services.
    |
    */

    'url' => env('FRONTEND_URL', 'http://localhost:8080'),

    /*
    |--------------------------------------------------------------------------
    | Frontend Routes
    |--------------------------------------------------------------------------
    |
    | These are used to route traffic to specific places on the frontend app.
    | They can be appended to the frontend url and will require the use of
    | the full path to the route. You may omit trailing slashes though.
    |
    */

    'routes' => [
        'email_verification' => env('EMAIL_VERIFICATION_ROUTE', '/email/verify?verificationUrl='),
    ],
];