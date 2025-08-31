<?php

declare(strict_types=1);

namespace Wazum\DatetimeFractionalSeconds\Core\Database\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MariaDBPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Platforms\PostgreSQLPlatform;

final class DateTimeSqlDeclarationFactory
{
    /**
     * @var array<string, DateTimeSqlDeclaration|null>
     */
    private static array $cache = [];

    public static function createFromPlatform(AbstractPlatform $platform): ?DateTimeSqlDeclaration
    {
        $key = $platform::class;
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }

        $instance = null;
        if ($platform instanceof MariaDBPlatform || $platform instanceof MySQLPlatform) {
            $instance = new MySqlPlatformDateTimeSqlDeclaration();
        } elseif ($platform instanceof PostgreSQLPlatform) {
            $instance = new PostgreSqlPlatformDateTimeSqlDeclaration();
        }

        return self::$cache[$key] = $instance;
    }
}
