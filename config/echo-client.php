<?php

return [

    /**
     * Host
     */
    'host' => env('ECHO_SERVER', '127.0.0.1'),

    /**
     * Port
     */
    'port' => env('ECHO_SERVER_PORT', '6010'),

    /**
     * protocol
     */
    'protocol' => env('ECHO_SERVER_PROTOCOL', 'wss'),

    /**
     * Channel to send event by default
     */
    'channel' => env('ECHO_CHANNEL_NAME', 'echo-channel'),

    /**
     * Config SSL, set false to dev ssl mode 
     */
    'verify_peer' => env('ECHO_SERVER_VERIFY_PEER', true),
    'verify_peer_name' => env('ECHO_SERVER_VERIFY_PEER_NAME', true),
];
