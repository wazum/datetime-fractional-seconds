<?php

declare(strict_types=1);

namespace Wazum\DatetimeFractionalSeconds\Core\Database\Types;

readonly class MySqlPlatformDateTimeSqlDeclaration extends AbstractDateTimeSqlDeclaration implements DateTimeSqlDeclaration
{
    public function getSQLDeclaration(array $column): string
    {
        if (isset($column['version']) && $column['version']) {
            return 'TIMESTAMP';
        }
        $precision = $this->getPrecision($column['length'] ?? 0);
        if ($precision > 0) {
            return sprintf('DATETIME(%d)', $precision);
        }

        return 'DATETIME';
    }
}
