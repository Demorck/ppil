<?php


namespace App\Tests\Helpers;

use Doctrine\ORM\EntityManager;

class Utils
{
    public static function resetDB(EntityManager $entityManager)
    {
        $connection = $entityManager->getConnection();
        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=0');
        $tables = $connection->fetchFirstColumn('SHOW TABLES');

        foreach ($tables as $table) {
            $connection->executeStatement("TRUNCATE TABLE $table");
        }

        $connection->executeStatement('SET FOREIGN_KEY_CHECKS=1');
    }
}