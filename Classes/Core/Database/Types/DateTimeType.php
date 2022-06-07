<?php

declare(strict_types=1);

namespace Wazum\DatetimeFractionalSeconds\Core\Database\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MariaDb1027Platform;
use Doctrine\DBAL\Platforms\MySQL57Platform;
use Doctrine\DBAL\Platforms\PostgreSQL100Platform;
use Doctrine\DBAL\Types\ConversionException;

final class DateTimeType extends \Doctrine\DBAL\Types\DateTimeType
{
    private const SUPPORTED_PLATFORMS = [
        MariaDb1027Platform::class,
        MySQL57Platform::class,
        PostgreSQL100Platform::class
    ];

    /**
     * {@inheritdoc}
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$this->isSupportedPlatform($platform)) {
            return parent::convertToDatabaseValue($value, $platform);
        }

        $dateTimeFormat = $platform->getDateTimeFormatString();

        return $value->format("{$dateTimeFormat}.u");
    }

    /**
     * {@inheritdoc}
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (!$this->isSupportedPlatform($platform)) {
            return parent::convertToPHPValue($value, $platform);
        }

        if ($value === null || $value instanceof \DateTimeInterface) {
            return $value;
        }

        $dateTimeFormat = $platform->getDateTimeFormatString();
        $dateTime = \DateTime::createFromFormat("{$dateTimeFormat}.u", $value);

        if (!$dateTime) {
            $dateTime = \date_create_immutable($value);
        }

        if (!$dateTime) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }

        return $dateTime;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        if (!$this->isSupportedPlatform($platform)) {
            return parent::getSQLDeclaration($column, $platform);
        }

        if (isset($column['version']) && $column['version']) {
            return 'TIMESTAMP';
        }
        if (isset($column['length']) && is_numeric($column['length'])) {
            return sprintf('DATETIME(%d)', (int)$column['length']);
        }

        return 'DATETIME';
    }

    private function isSupportedPlatform(AbstractPlatform $platform): bool
    {
        return in_array(get_class($platform), self::SUPPORTED_PLATFORMS);
    }
}
