<?php

declare(strict_types=1);

namespace Wazum\DatetimeFractionalSeconds\Core\Database;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use Throwable;
use TYPO3\CMS\Core\Database\Connection;
use Wazum\DatetimeFractionalSeconds\Core\Database\Types\DateTimeType;

final class ConnectionPool extends \TYPO3\CMS\Core\Database\ConnectionPool
{
    protected function getDatabaseConnection(array $connectionParams): Connection
    {
        try {
            Type::overrideType(Types::DATETIME_MUTABLE, DateTimeType::class);
        } catch (Throwable) {
            // Ignore
        }

        return parent::getDatabaseConnection($connectionParams);
    }
}
