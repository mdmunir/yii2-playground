<?php
use yii\helpers\ArrayHelper;

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'name' => 'Playground',
    'aliases' => [
        '@app' => dirname(__DIR__),
        '@bower' => '@vendor/bower-asset',
        '@PhpMimeMailParser' => '@app/classes/mail_parser',
    ],
    'modules' => [
        'task' => [
            'class' => 'task\Module',
        ]
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'clientProfile' => [
            'class' => 'dee\tools\State'
        ],
        'formatter' => [
            'class' => 'app\classes\Formatter',
            'currencyCode' => 'IDR'
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
        ],
        'notification' => [
            'class' => 'app\classes\Notification',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\Google',
                    'normalizeUserAttributeMap' => [
                        'email' => ['emails', 0, 'value'],
                        'name' => 'displayName',
                        'profile' => 'url',
                        'avatar' => function ($attributes) {
                            return str_replace('?sz=50', '', ArrayHelper::getValue($attributes, 'image.url'));
                        },
                    ]
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'normalizeUserAttributeMap' => [
                        'avatar' => function ($attributes) {
                            return "https://graph.facebook.com/{$attributes['id']}/picture?width=1920";
                        },
                        'profile' => function ($attributes) {
                            return "https://www.facebook.com/{$attributes['id']}";
                        },
                    ],
                ],
                'github' => [
                    'class' => 'yii\authclient\clients\GitHub',
                    'normalizeUserAttributeMap' => [
                        'avatar' => 'avatar_url',
                        'nickname' => 'login',
                        'profile' => 'html_url'
                    ]
                ],
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'normalizeUserAttributeMap' => [
                        'avatar' => 'imageUrl',
                    ]
                ],
            ],
        ],
    ],
    //'language' => 'id-ID'
];
