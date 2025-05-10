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

    'banguat'=> [
      'wsdl' => "https://www.banguat.gob.gt/variables/ws/TipoCambio.asmx?WSDL",
        'methods'=> [
            'availableVariables' => 'VariablesDisponibles',
            'ExchangeRateDiaString' => 'TipoCambioDiaString',
            'ChangeTypeStartDate' => 'TipoCambioFechaInicial',
            'ExchangeRateInitialDateCurrency' => 'TipoCambioFechaInicialMoneda',
            'ExchangeRateRange' => 'TipoCambioRango',
            'ExchangeRateRangeCurrency' => 'TipoCambioRangoMoneda',
            'Variables' => 'Variables',
        ]
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
