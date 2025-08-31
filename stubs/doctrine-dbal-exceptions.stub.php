<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Types\Exception;

class InvalidType extends \InvalidArgumentException
{
    /**
     * @param list<string> $expected
     */
    public static function new($value, string $toType, array $expected): self
    {
        return new self();
    }
}

class InvalidFormat extends \InvalidArgumentException
{
    public static function new(string $value, string $toType, string $expectedFormat, ?\Throwable $previous = null): self
    {
        return new self();
    }
}
