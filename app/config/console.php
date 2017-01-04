<?php
$params = array_merge(
    require(__DIR__ . '/params.php'), require(__DIR__ . '/params-local.php')
);
return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'queue' => [
            'class' => 'dee\queue\QueueController',
            'sleep' => YII_DEBUG ? 1 : 0,
            'scriptFile' => '@app/../yii'
        ],
        'scheduler' => [
            'class' => 'dee\console\SchedulerController',
            'scriptFile' => '@app/../yii',
            'commands' => [
                'cron/order-expire' => '@fiveMinutes',
                'cron/synchron-travel-search' => '@monday',
            ],
        ]
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'rules' => require 'url-rules.php',
            'baseUrl' => '',
            'scriptUrl' => '/index.php',
            'hostInfo' => 'http://beta.piknikio.com',
        ],
        'apiUrlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'rules' => require __DIR__ . '/../../rest/config/url-rules.php',
            'baseUrl' => '',
            'scriptUrl' => '/index.php',
            'hostInfo' => 'http://api.beta.piknikio.com',
        ],
    ],
    'params' => $params,
];
