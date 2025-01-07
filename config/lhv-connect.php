<?php

return [
    'tmp_folder' => '/tmp',
    'accounts' => [
        'sandbox' => [
            'url' => 'https://connect.prelive.lhv.eu',
            'cert' => [
                // Path to your .p12 file (private key + LHV public certificate)
                'path' => '',
                // Passphrase for the .p12 file
                'password' => '',
            ],
            // Path to root CA certificate provided by LHV
            'verify' => 'path_to_lhv_rootca.cer',
            'IBAN' => '',
            'name' => '',
            'bic' => 'LHVBEE22',
        ],

        'live' => [
            'url' => 'https://connect.lhv.eu',
            'cert' => [
                // Path to your .p12 file (private key + LHV public certificate)
                'path' => '',
                // Passphrase for the .p12 file
                'password' => '',
            ],
            // Path to root CA certificate provided by LHV
            'verify' => 'path_to_lhv_rootca.cer',
            'IBAN' => '',
            'name' => '',
            'bic' => 'LHVBEE22',
        ],

        // Add more accounts if needed...
    ],
];
