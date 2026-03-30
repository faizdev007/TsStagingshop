<?php

return
    [
        'SSL_PEM'       => base_path().'\pem\pwwebmasters.pem',
        'SSL_CERT'      => base_path().'\crt\zpg_realtime_listings_1563187935910769_20190715-20290712.crt',
        'ENVIRONMENT'   => env('ZOOPLA_ENVIRONMENT', 'test'),
        'BRANCH_NAME'   => env('ZOOPLA_BRANCH_NAME', ''),
        'FTP_HOST'      => '185.217.42.129', // Change to given FTP Host
        'FTP_USER'      => 'propwebdev', // Change to given Username
        'FTP_PASSWORD'  => 'QzjsFRq21pqS', // Change to given password....
    ];