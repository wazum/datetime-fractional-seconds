<?php

declare(strict_types=1);

namespace Wazum\DatetimeFractionalSeconds\Core\Database\Types;

readonly class AbstractDateTimeSqlDeclaration
{
    protected function getPrecision($value): int
    {
        $precision = (int) $value;
        if ($precision < 1 || $precision > 6) {
            $precision = 0;
        }

        return $precision;
    }
}
