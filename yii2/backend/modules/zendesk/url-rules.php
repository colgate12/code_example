<?php

use yii\rest\UrlRule;

return [
    [
        'class' => UrlRule::class,
        'controller' => ['zendesk/feedbacks' => 'zendesk/feedback'],
        'extraPatterns' => [
            'POST' => 'feedbacks',
            'POST status' => 'status',
            'POST update-ticket-status' => 'update-ticket-status',
            'POST create-ticket' => 'create-ticket',
            'POST save-comment' => 'save-comment',
            'OPTIONS <action>' => 'options'
        ],
    ],
    [
        'class' => UrlRule::class,
        'controller' => ['zendesk/channel' => 'zendesk/channel'],
        'extraPatterns' => [
            'GET get-manifest' => 'get-manifest',
            'POST pull' => 'pull',
            'POST channelback' => 'channelback',
            'POST callback' => 'callback',
            'POST admin-ui' => 'admin-ui',
            'POST get-storage-file' => 'get-storage-file',
            'OPTIONS <action>' => 'options'
        ]
    ],
];
