<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Studio details
    |--------------------------------------------------------------------------
    |
    | Contact details render only when set, so no placeholder email or booking
    | link ever reaches the page. They also feed the Organization schema.
    |
    */

    'name' => 'The Ordinary Agency',

    'email' => env('AGENCY_EMAIL'),

    'booking_url' => env('AGENCY_BOOKING_URL'),

    'social' => array_filter([
        'Instagram' => env('AGENCY_INSTAGRAM_URL'),
        'LinkedIn' => env('AGENCY_LINKEDIN_URL'),
        'Behance' => env('AGENCY_BEHANCE_URL'),
        'Dribbble' => env('AGENCY_DRIBBBLE_URL'),
    ]),

    'og_image' => 'img/og-default.jpg',

];
