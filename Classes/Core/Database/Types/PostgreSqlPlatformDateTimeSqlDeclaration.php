<?php

declare(strict_types=1);

namespace Wazum\DatetimeFractionalSeconds\Core\Database\Types;

readonly class PostgreSqlPlatformDateTimeSqlDeclaration extends AbstractDateTimeSqlDeclaration implements DateTimeSqlDeclaration
{
    /**
     * @param array<string, mixed> $column
     */
    public function getSQLDeclaration(array $column): string
    {
        $precision = $this->getPrecision($column['length'] ?? null);
        if (null === $precision) {
            return 'TIMESTAMP WITHOUT TIME ZONE';
        }

        return sprintf('TIMESTAMP(%d) WITHOUT TIME ZONE', $precision);
    }
}
