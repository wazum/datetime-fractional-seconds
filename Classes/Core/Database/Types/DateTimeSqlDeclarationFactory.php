<?php

declare(strict_types=1);

namespace Wazum\DatetimeFractionalSeconds\Core\Database\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use function sprintf;
use function stripos;

final class DateTimeSqlDeclarationFactory
{
    public static function createFromPlatform(AbstractPlatform $platform): ?DateTimeSqlDeclaration
    {
        $shortClassName = (new \ReflectionClass($platform))->getShortName();
        if (stripos($shortClassName, 'mysql') !== false || stripos($shortClassName, 'mariadb') !== false) {
            return new MySqlPlatformDateTimeSqlDeclaration();
        }

        if (stripos($shortClassName, 'postgresql') !== false) {
            return new PostgreSqlPlatformDateTimeSqlDeclaration();
        }

        if (class_exists(sprintf('%s\%sDateTimeSqlDeclaration', __NAMESPACE__, $shortClassName))) {
            return new (sprintf('%s\%sDateTimeSqlDeclaration', __NAMESPACE__, $shortClassName))();
        }

        return null;
    }
}
