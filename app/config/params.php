<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => ['support@piknikio.com' => 'Piknikio Support'],
    'user.passwordResetTokenExpire' => 3600,
    'user.activateExpire' => 3 * 24 * 3600,
    'user.rememberMeDuration' => 3 * 24 * 3600,
    'dee.migration.path' => [
        '@mdm/upload/migrations',
    ],
    'google.map' => [],
    'api.url.config' => [],
    'web.url.config' => [],
    'fcm.key' => '',
    'google.key' => '',
    'pusher.key' => '',
    'pusher.secret' => '',
    'pusher.app_id' => '',
];
