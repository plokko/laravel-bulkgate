<?php

/**
 * laravel-bulkgate config
 */
return [
    /** Your application id */
    'application_id' => env('BULKGATE_APPLICATION_ID'),
    /** Your application token */
    'application_token' => env('BULKGATE_APPLICATION_TOKEN'),
    /** Request timeout, in seconds */
    'timeout' => 30,
    /** Enable debug for all API requests */
    'debug' => false,

    //'sms_sender' => env('BULKGATE_SMS_SENDER',null),
    'default_sender' => 'default',
    'senders' => [
        'default' => [
            /**
             * @see https://github.com/BulkGate/php-sdk/blob/main/docs/sender.md
             */
            'tag' => env('BULKGATE_SENDER_TAG', null),

            'sms' => [
                /** Sender id */
                'id' => env('BULKGATE_SENDER_SMS_ID', 'gText'),
                /** Sender id value (name) */
                'value' => env('BULKGATE_SENDER_NAME'),
                /** Support unicode? */
                'unicode' => env('BULKGATE_SENDER_SMS_UNICODE'),
            ],
            'viber' => [
                /** Optional Viber name */
                'name' => env('BULKGATE_SENDER_NAME'),
                /** Optional Viber image */
                'image' => env('BULKGATE_SENDER_IMAGE', null),
                /** Optional Viber button caption */
                'button' => env('BULKGATE_SENDER_VIBER_BUTTON', null),
            ],

        ],
    ],

    /**
     * Do not send SMS, just simualte and send to logs.
     * NOTE: THIS WILL DISABLE SENDING ANY SMS if true.
     *
     * @var bool
     */
    'simulate_send' => (bool) env('BULKGATE_SIMULATE_SEND', false),
];
