<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'queue' => [
            'class' => \yii\queue\amqp_interop\Queue::class,
            'port' => 5672,
            'user' => 'root',
            'password' => '',
            'queueName' => 'paysystem',
            'driver' => yii\queue\amqp_interop\Queue::ENQUEUE_AMQP_LIB,
            'dsn' => 'amqp:',
        ],
    ],
    'bootstrap' => [
        'queue', // Компонент регистрирует свои консольные команды
    ],

];
