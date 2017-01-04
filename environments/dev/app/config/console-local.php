<?php
return [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
            'generators' => [
                'crud' => ['class' => 'dee\gii\generators\crud\Generator'],
                'migration' => ['class' => 'dee\gii\generators\migration\Generator'],
//                'angular' => ['class' => 'dee\gii\generators\angular\Generator'],
//                'mvc' => ['class' => 'dee\gii\generators\mvc\Generator'],
            ]
        ]
    ],
    'components' => [
        'request' => [
            'class' => 'dee\console\Request',
            'rules' => [
                'cmd/{name}' => 'cmd/index'
            ]
        ],
        'urlManager' => [
            //'hostInfo' => 'http://playground.dev',
        ],
        'apiUrlManager' => [
            //'hostInfo' => 'http://api.playground.dev',
        ],
    ],
];
