<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Monolog\Logger;

$isProduction = $_ENV['APPLICATION_ENV'] === 'production';

return function (ContainerBuilder $containerBuilder) use ($isProduction) {
    // Global Settings Object
    $containerBuilder->addDefinitions([
        'settings' => [
            // Is debug mode
            'debug' => !$isProduction,
            'displayErrorDetails' => !$isProduction, // Should be set to false in production
            'logErrors' => true,
            'logErrorDetails' => true,
            'logger' => [
                'name' => 'slim-app',
                'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../var/logs/app.log',
                'level' => Logger::DEBUG,
            ],
            'db' => [
                'driver' => $_ENV['DB_DRIVER'],
                'host' => $_ENV['DB_HOST'],
                'port' => $_ENV['DB_PORT'],
                'dbname' => $_ENV['DB_DATABASE'],
                'user' => $_ENV['DB_USERNAME'],
                'password' => $_ENV['DB_PASSWORD'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                //'driverOptions' => [
                //    // Turn off persistent connections
                //    PDO::ATTR_PERSISTENT => false,
                //    // Enable exceptions
                //    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                //    // Emulate prepared statements
                //    PDO::ATTR_EMULATE_PREPARES => true,
                //    // Set default fetch mode to array
                //    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                //    // Set character set
                //    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
                //],
            ],
            'doctrine' => [
                // if true, metadata caching is forcefully disabled
                'dev_mode' => true,

                // path where the compiled metadata info will be cached
                // make sure the path exists and it is writable
                'cache_dir' => __DIR__ . '/../var/cache',

                // you should add any other path containing annotated entity classes
                'metadata_dirs' => [ __DIR__ . '/../config/doctrine/mappings' ],

                'dbal' => [
                    'types' => [
                        'uuid' => App\Infrastructure\Persistence\Task\Doctrine\Orm\Type\Uuid::class,
                        'timestamp' => App\Infrastructure\Persistence\Task\Doctrine\Orm\Type\Timestamp::class,
                    ],
                    'mapping_types' => []
                ]
            ]
        ],
    ]);
};
