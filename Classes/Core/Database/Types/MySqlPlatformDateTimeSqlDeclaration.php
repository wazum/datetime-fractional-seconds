<?php

declare(strict_types=1);

namespace Wazum\DatetimeFractionalSeconds\Core\Database\Types;

readonly class MySqlPlatformDateTimeSqlDeclaration extends AbstractDateTimeSqlDeclaration implements DateTimeSqlDeclaration
{
    /**
     * @param array<string, mixed> $column
     */
    public function getSQLDeclaration(array $column): string
    {
        if (isset($column['version']) && $column['version']) {
            return 'TIMESTAMP';
        }
        $precision = $this->getPrecision($column['length'] ?? null);
        if (null !== $precision && $precision > 0) {
            return sprintf('DATETIME(%d)', $precision);
        }

        return 'DATETIME';
    }
}
