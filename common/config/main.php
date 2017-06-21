<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'authManager'=>[//授权管理器，数据库存放授权数据
            'class'=>\yii\rbac\DbManager::className(),
        ]
    ],
];
