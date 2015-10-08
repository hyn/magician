<?php
return [
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