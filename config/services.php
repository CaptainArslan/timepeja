<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'fcm' => [
        'key' => env('FCM_KEY'),
        'project_id' => env('FCM_PROJECT_ID'),
        'credentials_file_path' => env('FCM_CREDENTIALS_FILE_PATH', storage_path('app/firebase/firebase.json')),
    ],

    'sms' => [
        'url' => env('SMS_URL'),
        'login_id' => env('SMS_LOGIN_ID'),
        'login_password' => env('SMS_LOGIN_PASSWORD'),
        'mask' => env('SMS_MASK'),
        'unicode' => env('SMS_UNICODE'),
        'short_code_prefered' => env('SMS_SHORT_CODE_PREFERED'),
    ],

];
