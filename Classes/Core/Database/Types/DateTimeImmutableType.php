<?php

declare(strict_types=1);

namespace Wazum\DatetimeFractionalSeconds\Core\Database\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidFormat;
use Doctrine\DBAL\Types\Exception\InvalidType;

final class DateTimeImmutableType extends \Doctrine\DBAL\Types\DateTimeImmutableType
{
    /**
     * @throws InvalidType
     */
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }
        if ($value instanceof \DateTimeImmutable) {
            $format = $platform->getDateTimeFormatString() . '.u';

            return $value->format($format);
        }
        throw InvalidType::new($value, static::class, ['null', \DateTimeImmutable::class]);
    }

    /**
     * @throws InvalidFormat|InvalidType
     */
    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?\DateTimeImmutable
    {
        if (null === $value || $value instanceof \DateTimeImmutable) {
            return $value;
        }
        if (!is_string($value)) {
            throw InvalidType::new($value, static::class, ['null', 'string', \DateTimeImmutable::class]);
        }
        $format = $platform->getDateTimeFormatString() . '.u';
        $dateTime = \DateTimeImmutable::createFromFormat($format, $value);
        if ($dateTime instanceof \DateTimeImmutable) {
            return $dateTime;
        }
        try {
            return new \DateTimeImmutable($value);
        } catch (\Throwable $e) {
            throw InvalidFormat::new($value, static::class, $format, $e);
        }
    }

    /**
     * @param array<array-key, mixed> $column
     */
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $sqlDeclaration = DateTimeSqlDeclarationFactory::createFromPlatform($platform);
        if (null !== $sqlDeclaration) {
            return $sqlDeclaration->getSQLDeclaration($column);
        }

        return parent::getSQLDeclaration($column, $platform);
    }
}
