<?php

return [
    'ip-api' => [
        'url' => 'http://ip-api.com/json/',

        /**
         * numeric representation of required fields
         * https://ip-api.com/docs/api:json - look for 'Returned data' section
         */
        'fields_set' => '16576',
    ],

    'open-weather' => [
        'api-key' => env('OPENWEATHER_API_KEY'),
        'url' => 'https://api.openweathermap.org/data/3.0/onecall',
    ],
];
