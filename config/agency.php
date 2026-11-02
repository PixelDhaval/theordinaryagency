<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Studio details
    |--------------------------------------------------------------------------
    |
    | Contact details render only when set, so no placeholder email or booking
    | link ever reaches the page. They also feed the Organization schema. The
    | studio's own email and phone are the defaults; the env can override them.
    |
    */

    'name' => 'The Ordinary Agency',

    'email' => env('AGENCY_EMAIL', 'hello@theordinaryagency.com'),

    'phone' => env('AGENCY_PHONE', '+91 99744 57275'),

    'booking_url' => env('AGENCY_BOOKING_URL'),

    'social' => array_filter([
        'Instagram' => env('AGENCY_INSTAGRAM_URL'),
        'LinkedIn' => env('AGENCY_LINKEDIN_URL'),
        'Behance' => env('AGENCY_BEHANCE_URL'),
        'Dribbble' => env('AGENCY_DRIBBBLE_URL'),
    ]),

    'og_image' => 'img/og-default.jpg',

];
