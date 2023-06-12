<?php

declare(strict_types=1);

namespace Wazum\DatetimeFractionalSeconds\Core\Database\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;

final class DateTimeType extends \Doctrine\DBAL\Types\DateTimeType
{
    /**
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        return $value->format("{$platform->getDateTimeFormatString()}.u");
    }

    /**
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value || $value instanceof \DateTimeInterface) {
            return $value;
        }

        $dateTime = \DateTime::createFromFormat("{$platform->getDateTimeFormatString()}.u", $value);

        if (!$dateTime) {
            $dateTime = \date_create_immutable($value);
        }

        if (!$dateTime) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateTimeFormatString());
        }

        return $dateTime;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $sqlDeclaration = DateTimeSqlDeclarationFactory::createFromPlatform($platform);
        if (null !== $sqlDeclaration) {
            return $sqlDeclaration->getSqlDeclaration($column);
        }

        return parent::getSQLDeclaration($column, $platform);
    }
}
