<?php

declare(strict_types=1);

namespace Doctrine\DBAL\Types {
    class Type
    {
        public static function overrideType(string $name, string $className): void
        {
        }

        public static function getType(string $name): self
        {
            return new self();
        }
    }
}

namespace Doctrine\DBAL\Types {
    use Doctrine\DBAL\Platforms\AbstractPlatform;

    class DateTimeType extends Type
    {
        public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
        {
            return '';
        }

        public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
        {
            return null;
        }

        public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?\DateTime
        {
            return null;
        }
    }

    class DateTimeImmutableType extends Type
    {
        public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
        {
            return '';
        }

        public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
        {
            return null;
        }

        public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?\DateTimeImmutable
        {
            return null;
        }
    }
}
