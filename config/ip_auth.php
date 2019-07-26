<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Action
    |--------------------------------------------------------------------------
    |
    | By default, if the request matches none of the defined derivatives we can 
    | either deny or allow the user. You can specify this default action here.
    |
    */

    'default_action' => 'deny',
 
    /*
    |--------------------------------------------------------------------------
    | Directives
    |--------------------------------------------------------------------------
    |
    | Directives specify which groups of addresses are allowed or denied access. 
    | A directive consists of a list name and an action to take if an address exists 
    | within that list. Directives are processed sequentially, the result of the first 
    | matched directive will be returned. By default, the blacklist is processed before 
    | the whitelist, if an address was in both lists it would denied as the first 
    | directive to be processed is the blacklist. You can reorder the directives to 
    | suit your needs.
    |
    | You can create as many directives as you would like, by default there are two 
    | whitelist & blacklist.
    |
    */   

    'directives' => [
        'blacklist' => 'deny',
        'whitelist' => 'allow',
    ],

    /*
    |--------------------------------------------------------------------------
    | List Repositories
    |--------------------------------------------------------------------------
    |
    | Out of the box the package supports loading address lists from configuration 
    | or the database. The default is configuration, you may configure whitelisted 
    | or blacklisted addresses at the bottom of this file.
    |
    */

    'default_repository' => 'config',

    'repositories' => [

        'config' => [
            'key' => 'ip_auth',
        ],

        'database' => [
            'connection' => null,
            'table' => 'ip_auth_access_list',
        ]

    ],

    /*
    |--------------------------------------------------------------------------
    | Configuration Based Address Lists
    |--------------------------------------------------------------------------
    |
    | If you decide to use the default configuration list repository, you can 
    | simply add the addresses you wish to control below.
    |
    */
   
    'addresses' => [

        'whitelist' => [
            //'127.0.0.1',
            //'192.168.1.*',
            //192.168.1.0/24
            //192.168.1.1 255.255.255.0
            //192.168.1.1-192.168.1.10
            //2001:cdba:0000:0000:0000:0000:3257:*
        ],

        'blacklist' => [
            //'127.0.0.1',
            //'192.168.99.1',
            //192.168.1.*
            //192.168.1.0/24
            //192.168.1.1 255.255.255.0
            //192.168.1.1-192.168.1.10
            //2001:cdba:0000:0000:0000:0000:3257:*
        ],

        'custom_list' => [
            // place custom address definitions here.
        ]
    ]
];