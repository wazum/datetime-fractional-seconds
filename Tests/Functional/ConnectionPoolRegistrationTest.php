<?php

declare(strict_types=1);

namespace Wazum\DatetimeFractionalSeconds\Tests\Functional;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;
use TYPO3\CMS\Core\Database\ConnectionPool as CoreConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use Wazum\DatetimeFractionalSeconds\Core\Database\Types\DateTimeImmutableType as WazumDateTimeImmutableType;
use Wazum\DatetimeFractionalSeconds\Core\Database\Types\DateTimeSqlDeclarationFactory;
use Wazum\DatetimeFractionalSeconds\Core\Database\Types\DateTimeType as WazumDateTimeType;

final class ConnectionPoolRegistrationTest extends FunctionalTestCase
{
    /**
     * @var list<string>
     */
    protected array $testExtensionsToLoad = ['datetime_fractional_seconds'];

    public function testConnectionPoolRegistersCustomTypes(): void
    {
        $pool = GeneralUtility::makeInstance(CoreConnectionPool::class);
        $connection = $pool->getConnectionByName(CoreConnectionPool::DEFAULT_CONNECTION_NAME);
        $platform = $connection->getDatabasePlatform();

        $mutable = Type::getType(Types::DATETIME_MUTABLE);
        $immutable = Type::getType(Types::DATETIME_IMMUTABLE);

        self::assertInstanceOf(WazumDateTimeType::class, $mutable, 'Mutable datetime type not overridden');
        self::assertInstanceOf(WazumDateTimeImmutableType::class, $immutable, 'Immutable datetime type not overridden');

        $decl = DateTimeSqlDeclarationFactory::createFromPlatform($platform);
        self::assertNotNull($decl, 'No SQL declaration handler for platform');
    }

    public function testSqlDeclarationAndConversions(): void
    {
        $pool = GeneralUtility::makeInstance(CoreConnectionPool::class);
        $connection = $pool->getConnectionByName(CoreConnectionPool::DEFAULT_CONNECTION_NAME);
        $platform = $connection->getDatabasePlatform();

        $short = (new \ReflectionClass($platform))->getShortName();
        $isPostgres = false !== stripos($short, 'postgres');

        $decl = DateTimeSqlDeclarationFactory::createFromPlatform($platform);
        self::assertNotNull($decl);

        $got6 = $decl->getSQLDeclaration(['length' => 6]);
        $got0 = $decl->getSQLDeclaration(['length' => 0]);

        if ($isPostgres) {
            self::assertSame('TIMESTAMP(6) WITHOUT TIME ZONE', $got6);
            self::assertSame('TIMESTAMP WITHOUT TIME ZONE', $got0);
        } else {
            self::assertSame('DATETIME(6)', $got6);
            self::assertSame('DATETIME', $got0);
        }

        $mutable = new WazumDateTimeType();
        $immutable = new WazumDateTimeImmutableType();

        $dbValMutable = $mutable->convertToDatabaseValue(new \DateTime('2023-01-02 03:04:05.123456'), $platform);
        $dbValImmutable = $immutable->convertToDatabaseValue(new \DateTimeImmutable('2023-01-02 03:04:05.654321'), $platform);

        self::assertStringContainsString('.123456', (string) $dbValMutable, 'Mutable conversion missing microseconds');
        self::assertStringContainsString('.654321', (string) $dbValImmutable, 'Immutable conversion missing microseconds');
    }
}
