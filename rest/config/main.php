<?php
use yii\web\UrlManager;

$params = array_merge(
    require(__DIR__ . '/../../app/config/params.php'),
    require(__DIR__ . '/../../app/config/params-local.php')
);

return [
    'id' => 'app-rest',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log',
        [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
                'application/json' => 'json',
                //'text/html' => 'html',
                //'application/xml' => 'xml',
                '*' => 'json',
            ],
        ],
    ],
    'controllerNamespace' => 'rest\controllers',
    'controllerMap' => [
        'image' => 'app\controllers\ImageController',
    ],
    'defaultRoute' => 'default/index',
    'components' => [
        'user' => [
            'identityClass' => 'app\models\ar\Device',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'as profile' => 'rest\classes\UserInfo'
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require 'url-rules.php',
        ],
        'webUrlManager' => function(UrlManager $urlManager){
            if ($urlManager->baseUrl == '') {
                $config = [
                    'baseUrl' => '',
                    'scriptUrl' => 'index.php',
                    'hostInfo' => strtr($urlManager->hostInfo, ['http://api.' => 'http://', 'https://api.' => 'https://']),
                ];
            } else {
                $config = [
                    'baseUrl' => strtr($urlManager->baseUrl, ['rest' => 'app']),
                    'scriptUrl' => strtr($urlManager->scriptUrl, ['rest' => 'app']),
                ];
            }
            $config = array_merge($config, [
                'rules' => require Yii::getAlias('@app/config/url-rules.php'),
                'enablePrettyUrl' => true,
                'showScriptName' => $urlManager->showScriptName,
            ], Yii::$app->params['web.url.config']);

            return new UrlManager($config);
        },
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'as filter' => 'rest\classes\FilterResponse'
        ]
    ],
    'params' => $params,
];
