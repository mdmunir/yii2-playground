<?php
use yii\web\UrlManager;


$params = array_merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-web',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\controllers',
    'modules' => [
        'api' => [
            'class' => 'rest\Module'
        ],
    ],
    'defaultRoute' => 'site/index',
    'components' => [
        'user' => [
            'identityClass' => 'app\models\ar\User',
            'loginUrl' => ['user/login'],
            'enableAutoLogin' => true,
            'as profile' => 'app\classes\UserInfo'
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'session' => [
            'class' => 'yii\web\DbSession'
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'rules' => require 'url-rules.php',
        ],
        'apiUrlManager' => function(UrlManager $urlManager){
            if ($urlManager->baseUrl == '') {
                $config = [
                    'baseUrl' => '',
                    'scriptUrl' => 'index.php',
                    'hostInfo' => strtr($urlManager->hostInfo, ['http://' => 'http://api.', 'https://' => 'https://api.']),
                ];
            } else {
                $config = [
                    'baseUrl' => strtr($urlManager->baseUrl, ['app' => 'rest']),
                    'scriptUrl' => strtr($urlManager->scriptUrl, ['app' => 'rest']),
                ];
            }
            $config = array_merge($config, [
                'rules' => require Yii::getAlias('@rest/config/url-rules.php'),
                'enablePrettyUrl' => true,
                'showScriptName' => $urlManager->showScriptName,
            ], Yii::$app->params['api.url.config']);
            
            return new UrlManager($config);
        },
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'assetManager' => [
            'assetMap' => [
                'jquery.js' => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js',
                'jquery-ui.js' => 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js',
                'jquery-ui.css' => 'https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css'
            ],
        ],
    ],
    'params' => $params,
];
