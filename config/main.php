<?php

$config=[
    'id'=>"yii-test-rhg",
    'basePath'=>dirname(__DIR__),
    'bootstrap'=>[],
    'aliases' => [
        '@bower' => '@vendor/bower-asset'
    ],
    'components'=>[
        'db'=>require __DIR__ . '../db.php',
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => require __DIR__.'/routes.php'
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8'
        ],
        'request' => [
            'cookieValidationKey' => 'yii-test-rhg',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser'
            ]
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
    ]
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;