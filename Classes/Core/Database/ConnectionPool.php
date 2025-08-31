<?php

declare(strict_types=1);

namespace Wazum\DatetimeFractionalSeconds\Core\Database;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use TYPO3\CMS\Core\Database\Connection;
use Wazum\DatetimeFractionalSeconds\Core\Database\Types\DateTimeImmutableType;
use Wazum\DatetimeFractionalSeconds\Core\Database\Types\DateTimeType;

final class ConnectionPool extends \TYPO3\CMS\Core\Database\ConnectionPool
{
    private static bool $customTypesRegistered = false;

    public function registerDoctrineTypes(): void
    {
        parent::registerDoctrineTypes();
        if (self::$customTypesRegistered) {
            return;
        }
        self::$customTypesRegistered = true;
        try {
            Type::overrideType(Types::DATETIME_MUTABLE, DateTimeType::class);
        } catch (\Throwable) {
        }
        try {
            Type::overrideType(Types::DATETIME_IMMUTABLE, DateTimeImmutableType::class);
        } catch (\Throwable) {
        }
    }

    public function getConnectionByName(string $connectionName): Connection
    {
        $this->registerDoctrineTypes();

        return parent::getConnectionByName($connectionName);
    }
}
