<?php





if (env('APP_ENV') === 'production') {

    return [
        'client-id' => env('GOVESB_CLIENT_ID_PROD', ''),
        'client-secret' => env('GOVESB_CLIENT_SECRET_PROD', ''),
        'private-key' => env('GOVESB_PRIVATE_KEY_PROD', ''),
        'esb-public-key' => env('GOVESB_ESB_PUBLIC_KEY_PROD', ''),
        'esb-token-url' => env('GOVESB_ESB_TOKEN_URL_PROD', ''),
        'esb-engine-url' => env('GOVESB_ESB_ENGINE_URL_PROD', ''),
        'nida-user-id' => env('GOVESB_NIDA_USER_ID_PROD', ''),
    ];


} else {

    return [
        'client-id' => env('GOVESB_CLIENT_ID_DEV', ''),
        'client-secret' => env('GOVESB_CLIENT_SECRET_DEV', ''),
        'private-key' => env('GOVESB_PRIVATE_KEY_DEV', ''),
        'esb-public-key' => env('GOVESB_ESB_PUBLIC_KEY_DEV', ''),
        'esb-token-url' => env('GOVESB_ESB_TOKEN_URL_DEV', ''),
        'esb-engine-url' => env('GOVESB_ESB_ENGINE_URL_DEV', ''),
        'nida-user-id' => env('GOVESB_NIDA_USER_ID_DEV', ''),
    ];

}


