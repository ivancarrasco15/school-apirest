<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

return function (): EntityManager {
    $paths = [
        __DIR__ . '/../src/Domain',
        __DIR__ . '/../src/Auth/Domain',
    ];

    $config = ORMSetup::createAttributeMetadataConfig($paths, true);
    $config->enableNativeLazyObjects(true);

    $dbParams = [
        'driver' => 'pdo_sqlite',
        'path'   => __DIR__ . '/../database/school.sqlite',
    ];

    $connection = DriverManager::getConnection($dbParams, $config);

    return new EntityManager($connection, $config);
};