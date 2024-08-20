<?php


$DB_HOST = getenv('DB_HOST');
$DB_USER = getenv('DB_USER');
$DB_PASSWORD = getenv('DB_PASSWORD');
$DB_DATABASE = getenv('DB_DATABASE');

return
    [
        'paths' => [
            'migrations' => __DIR__.'/db/migrations',
            'seeds' => __DIR__.'/db/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => 'development',
            'development' => [
                'adapter' => 'mysql',
                'host' => $DB_HOST,
                'name' => $DB_DATABASE,
                'user' => $DB_USER,
                'pass' => $DB_PASSWORD,
                'port' => '3306',
                'charset' => 'utf8',
            ],
        ],
        'version_order' => 'creation'
    ];
