<?php

use yii\rest\UrlRule;

return [
    [
        'class' => UrlRule::class,
        'controller' => ['markets/sources-list' => 'gomerItems/source'],
        'extraPatterns' => [
            'GET' => 'source-list',
            'OPTIONS <action>' => 'options'
        ]
    ],
    [
        'class' => UrlRule::class,
        'controller' => ['markets/source' => 'gomerItems/source'],
        'extraPatterns' => [
            'PUT synchronization' => 'synchronization',
            'GET last-sync-history' => 'last-sync-history',
            'OPTIONS <action>' => 'options'
        ]
    ],
    [
        'class' => UrlRule::class,
        'controller' => ['items' => 'gomerItems/item'],
        'extraPatterns' => [
            'PUT accept-changes' => 'accept-changes',
            'PUT mass-update' => 'mass-update',
            'GET details' => 'details',
            'OPTIONS <action>' => 'options'
        ]
    ],
    [
        'class' => UrlRule::class,
        'controller' => ['categories' => 'gomerItems/category'],
        'extraPatterns' => [
            'PUT bind' => 'bind',
            'GET unlink' => 'unlink',
            'OPTIONS <action>' => 'options'
        ]
    ],
    [
        'class' => UrlRule::class,
        'controller' => ['attributes' => 'gomerItems/attributes'],
        'extraPatterns' => [
            'GET list' => 'list',
            'PUT bind' => 'bind',
            'OPTIONS <action>' => 'options'
        ]
    ],
    [
        'class' => UrlRule::class,
        'controller' => ['items/file' => 'gomerItems/file'],
        'extraPatterns' => [
            'POST' => 'create-export',
            'GET' => 'export',
            'OPTIONS <action>' => 'options'
        ]
    ],
    [
        'class' => UrlRule::class,
        'controller' => ['items/file-mirror' => 'gomerItems/file-mirror'],
        'extraPatterns' => [
            'GET' => 'download',
            'OPTIONS <action>' => 'options'
        ]
    ],
    [
        'class' => UrlRule::class,
        'controller' => ['price-validator' => 'gomerItems/price-validator'],
        'extraPatterns' => [
            'POST validate' => 'validate',
            'OPTIONS <action>' => 'options'
        ]
    ],
];
