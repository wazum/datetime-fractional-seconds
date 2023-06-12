<?php

declare(strict_types=1);

namespace Wazum\DatetimeFractionalSeconds\Core\Database\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;

final readonly class DateTimeSqlDeclarationFactory
{
    public static function createFromPlatform(AbstractPlatform $platform): ?DateTimeSqlDeclaration
    {
        $shortClassName = (new \ReflectionClass($platform))->getShortName();
        if (false !== \stripos($shortClassName, 'mysql') || false !== \stripos($shortClassName, 'mariadb')) {
            return new MySqlPlatformDateTimeSqlDeclaration();
        }

        if (false !== \stripos($shortClassName, 'postgresql')) {
            return new PostgreSqlPlatformDateTimeSqlDeclaration();
        }

        if (class_exists(\sprintf('%s\%sDateTimeSqlDeclaration', __NAMESPACE__, $shortClassName))) {
            return new (\sprintf('%s\%sDateTimeSqlDeclaration', __NAMESPACE__, $shortClassName))();
        }

        return null;
    }
}
