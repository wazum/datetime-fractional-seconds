<?php

declare(strict_types=1);

namespace Wazum\DatetimeFractionalSeconds\Core\Database\Types;

interface DateTimeSqlDeclaration
{
    /**
     * @param array<string, mixed> $column
     */
    public function getSQLDeclaration(array $column): string;
}
