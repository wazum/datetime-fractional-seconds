<?php

namespace Wazum\DatetimeFractionalSeconds\Core\Database\Types;

interface DateTimeSqlDeclaration
{
    public function getSQLDeclaration(array $column): string;
}
