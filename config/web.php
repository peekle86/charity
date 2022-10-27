<?php

$params = require __DIR__ . '/params.php';
$db     = require __DIR__ . '/db.php';

if ( $_SERVER['SERVER_ADDR'] == '127.0.0.1' ) {
    $domain = 'http://admin.charity.loc';
} else {
    $domain = 'http://admin.kf-go.com';
//    $domain = 'https://admin.finesearch.org ';

}


$config = [
    'id'         => 'basic',
    'name'       => 'Charity Site',
    'basePath'   => dirname( __DIR__ ),
    'bootstrap'  => [ 'log' ],
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules'    => [

    ],
    'components' => [
        'telegram'     => [
            'class'    => 'aki\telegram\Telegram',
            'botToken' => '366300521:AAHNuvVnTEU8zHKglJmLX8QYjAO0ofJkanQ',
        ],
        'request'      => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'gWw7dT0Ou0iCJp8Nwt2SlOCo5qOU1Pvs',
            'parsers'             => [
                'application/json' => 'yii\web\JsonParser'
            ]
        ],
        'cache'        => [
            'class' => 'yii\caching\FileCache',
        ],
        'user'         => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer'       => [
            'class'            => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport'        => [
                'class'      => 'Swift_SmtpTransport',
                'encryption' => false,
                'host'       => 'localhost',
                'port'       => '25',
                'username'   => '',
                'password'   => '',
            ],
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => [ 'error', 'warning' ],
                ],
            ],
        ],
        'db'           => $db,
        'urlManager'   => [
            'enablePrettyUrl'     => true,
            'enableStrictParsing' => false,
            'showScriptName'      => false,
            'normalizer'          => [
                'class' => 'yii\web\UrlNormalizer',
            ],
            'rules'               => [
                '/'                    => 'site/index',
                'search'               => 'site/index',
                'logo'                  => 'search/logo',
                'change'               => 'site/index',
                'goto'                  => 'site/goto',
                'goto/<name>'        => 'site/goto',
                'reed/<name>'        => 'site/index',
                'search_req'           => 'search/search',
                'getpage'              => 'admin/getpage',
                'getsettings'          => 'admin/getsettings',
                'savesettings'         => 'admin/savesettings',
                //
                'signin'               => 'sign/signin',
                'signup'               => 'sign/signup',
                'confirm'              => 'sign/confirm',
                'restore_request'      => 'sign/restore',
                'change_password'      => 'sign/change',
                'restore'              => 'sign/restoreconfirm',
                'logout'               => 'sign/logout',
                'currentuser'          => 'sign/currentuser',
                'getuserlang'          => 'sign/getuserlang',


                //admin
                $domain . '/'          => 'admin/index',
                $domain . '/login'     => 'admin/index',
                $domain . '/dashboard' => 'admin/index',
                $domain . '/setup'     => 'admin/index',
                $domain . '/status'    => 'admin/status',

                $domain . '/usr'               => 'admin/index',
                $domain . '/usr/edit/<id:\d+>' => 'admin/index',
                $domain . '/usr/new'           => 'admin/index',


                $domain . '/orgs'               => 'admin/index',
                $domain . '/orgs/edit/<id:\d+>' => 'admin/index',
                $domain . '/orgs/new'           => 'admin/index',

                $domain . '/cats'               => 'admin/index',
                $domain . '/cats/edit/<id:\d+>' => 'admin/index',
                $domain . '/cats/new'           => 'admin/index',


                $domain . '/pgs'               => 'admin/index',
                $domain . '/pgs/edit/<id:\d+>' => 'admin/index',
                $domain . '/pgs/new'           => 'admin/index',

                $domain . '/lng'               => 'admin/index',
                $domain . '/lng/edit/<id:\d+>' => 'admin/index',
                $domain . '/lng/new'           => 'admin/index',


                $domain . '/ads'               => 'admin/index',
                $domain . '/ads/edit/<id:\d+>' => 'admin/index',
                $domain . '/ads/new'           => 'admin/index',


                //rest api actions//
                [ 'class' => 'yii\rest\UrlRule', 'controller' => 'user' ],
                [ 'class' => 'yii\rest\UrlRule', 'controller' => 'category' ],
                [ 'class' => 'yii\rest\UrlRule', 'controller' => 'organization' ],
                [ 'class' => 'yii\rest\UrlRule', 'controller' => 'user-role' ],
                [ 'class' => 'yii\rest\UrlRule', 'controller' => 'page' ],
                [ 'class' => 'yii\rest\UrlRule', 'controller' => 'lang' ],
                [ 'class' => 'yii\rest\UrlRule', 'controller' => 'link' ],

            ],
        ],
    ],
    'params'     => $params,
];

//if ( YII_ENV_DEV ) {
//    // configuration adjustments for 'dev' environment
//    $config['bootstrap'][]      = 'debug';
//    $config['modules']['debug'] = [
//        'class' => 'yii\debug\Module',
//        // uncomment the following to add your IP if you are not connecting from localhost.
//        //'allowedIPs' => ['127.0.0.1', '::1'],
//    ];
//
//    $config['bootstrap'][]    = 'gii';
//    $config['modules']['gii'] = [
//        'class' => 'yii\gii\Module',
//        // uncomment the following to add your IP if you are not connecting from localhost.
//        //'allowedIPs' => ['127.0.0.1', '::1'],
//    ];
//}

return $config;
