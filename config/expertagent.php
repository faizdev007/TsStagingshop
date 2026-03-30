<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ExpertAgent Feed Config
    |--------------------------------------------------------------------------
    |
    */

    'ftp' => [
        'host' => env('EXPERTAGENT_FTP_HOST', 'default'),
        'user' => env('EXPERTAGENT_FTP_USER', 'default'),
        'pass' => env('EXPERTAGENT_FTP_PASS', 'default'),
    ],

    'feed_files' => [
        // Our Feed ID => Feed filename
        // Note '1' reserved for Propertybase, '2' reserved for BHS Feed
        '3' => 'properties2.xml',
    ],

];