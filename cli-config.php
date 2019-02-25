<?php
include 'bootstrap.php';
use Doctrine\ORM\Tools\Console\ConsoleRunner,
    Doctrine\ORM\EntityManager;

    /*
try {
    $entityManager->getConnection()->connect();
}
catch(Exception $ex) {
    $connection = EntityManager::create([
        'driver'   => 'pdo_sqlite',
        'host'     => SQLITE_HOST,
        'user'     => SQLITE_USERNAME,
        'password' => SQLITE_PASSWORD,
        'charset' => 'UTF8'
    ], $config)->getConnection();
    $connection->executeUpdate('CREATE DATABASE '.SQLITE_DATABASE.' CHARACTER SET utf8 COLLATE utf8_general_ci');
}
*/
return ConsoleRunner::createHelperSet($entityManager);
?>