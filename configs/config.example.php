<?php
return [
    /**
     * Teamspeak server configurations
     */
    'teamspeak' => [
        // we allow different keys, so you can run multiple daemons at once
        'example' => [
            'host' => 'ts.example.org',
            'query_port' => 10011,
            'server_port' => 9987,
            // server query user
            'username' => '',
            // server query password
            'password' => '',
        ],
    ],
    /**
     * Notifier adapter configurations
     */
    'hipchat' => [
        // REQUIRED: access token generated in Hipchat
        'token' => '',
        // REQUIRED: room to send notification to
        'room' => '',
        // sender name
        'from' => 'Teamspeak',
        // whether to notify channel participants
        'notify' => false,
        // message format
        'format' => 'text',
        // color of the message: yellow, red, gray, green, purple, random
        'color' => 'random',
    ]
];