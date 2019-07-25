<?php

return [

    'repositories' => [
        'default' => 'config',

        'configuration' => [

            'eloquent' => [
                'model' => null,
            ]

        ]
    ],

    'default' => 'deny',
    
    'directives' => [
        'address_whitelisted' => 'allow',
        'address_blacklisted' => 'deny',
    ],

    'whitelist' => [
        //'127.0.0.1',
        //192.168.1.*
        //192.168.1/24
        //192.168.1.1/255.255.255.0
        //192.168.1.1-192.168.1.10
        //2001:cdba:0000:0000:0000:0000:3257:*
    ],

    'blacklist' => [
        //'127.0.0.1',
        //'192.168.99.1',
        //192.168.1.*
        //192.168.1/24
        //192.168.1.1/255.255.255.0
        //192.168.1.1-192.168.1.10
        //2001:cdba:0000:0000:0000:0000:3257:*
    ]
];