<?php
declare(strict_types=1);

use DI\ContainerBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\DBAL\Types\Type;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        EntityManagerInterface::class => function (ContainerInterface $c) {

            $configDb = $c->get('settings')['db'];

            $configDoctrine = $c->get('settings')['doctrine'];

            $config = Setup::createXMLMetadataConfiguration(
                $configDoctrine['metadata_dirs'],
                $configDoctrine['dev_mode'],
                null,
                new FilesystemCache(
                    $configDoctrine['cache_dir']
                )
            );

            $config->setMetadataCacheImpl(
                new FilesystemCache(
                    $configDoctrine['cache_dir']
                )
            );

            foreach ($configDoctrine['dbal']['types'] as $name => $typeClass) {
                Type::addType($name, $typeClass);
            }

            $entityManager = EntityManager::create(
                $configDb,
                $config
            );

            $databasePlatform = $entityManager->getConnection()->getDatabasePlatform();
            foreach ($configDoctrine['dbal']['mapping_types'] as $dbType => $doctrineType) {
                $databasePlatform->registerDoctrineTypeMapping($dbType, $doctrineType);
            }

            return $entityManager;
        },
    ]);
};
