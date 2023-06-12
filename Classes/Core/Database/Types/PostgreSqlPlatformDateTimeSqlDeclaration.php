<?php

declare(strict_types=1);

namespace Wazum\DatetimeFractionalSeconds\Core\Database\Types;

readonly class PostgreSqlPlatformDateTimeSqlDeclaration extends AbstractDateTimeSqlDeclaration implements DateTimeSqlDeclaration
{
    public function getSQLDeclaration(array $column): string
    {
        $precision = $this->getPrecision($column['length'] ?? 0);

        return sprintf('TIMESTAMP(%d) WITHOUT TIME ZONE', $precision);
    }
}
