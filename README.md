
# DATETIME with fractional seconds support

[![CI](https://github.com/wazum/datetime-fractional-seconds/actions/workflows/ci.yml/badge.svg)](https://github.com/wazum/datetime-fractional-seconds/actions/workflows/ci.yml)

A TYPO3 custom Doctrine type for DATETIME with fractional seconds support.

Compatible with TYPO3 12 LTS and 13 on PHP 8.2+.

## Use case

If you need more control over the order of records in your database you need fractional seconds precision.

Let's assume you have an event driven system and store events in the database and multiple events are stored in the same second (very likely), you need a higher precision timestamp if you ever want to process your event streams in the right order.

## Background

Doctrine (the ORM used by TYPO3) does not support fractional seconds for `DATETIME` fields.

There's an open issue about that here: https://github.com/doctrine/dbal/issues/2873

## Prerequisites

A database that supports DATETIME with fractional seconds is required. 👆
This extension supports MariaDB 10.2+, MySQL 5.7+ and PostgreSQL 10+.
Other database systems are not affected and will fall back to the default behavior.

You'll find a list of supported database systems in this comment here:
https://github.com/doctrine/dbal/issues/2873#issuecomment-602283947

Please submit a pull request if you need this behavior with TYPO3 and implemented it yourself.

## Installation

```
composer require "wazum/datetime-fractional-seconds"
```

## Usage

After installation every `DATETIME` field in `ext_tables.sql` with a _fractional seconds precision_ — e.g. `occurred_on DATETIME(6)` — will be created with this length (6).
Any `DATETIME` field without a precision length will be created without it (the default behavior).

With a precision on the database field you can then use a format that supports this in your code (the `.u` in the following example for a length of six).

| Format | Description                       | Example      |
|--------|-----------------------------------|--------------|
| v      | Milliseconds (up to three digits) | `12.345`     |
| u      | Microseconds (up to six digits)   | `45.654321`  |

See https://www.php.net/manual/en/datetimeimmutable.createfromformat.php for format options.

```php
private const DATETIME_FORMAT = 'Y-m-d H:i:s.u';

public function __invoke(SomethingHappened $event): void 
{
    $occurredOn = \DateTimeImmutable::createFromFormat(
        self::DATETIME_FORMAT,
        $event->getOccurredOn()
    );
}
```

## Possible problems

### Extending `ConnectionPool`

This extension extends the Core `ConnectionPool` to get a database connection.

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Database\ConnectionPool::class] = [
        'className' => \Wazum\DatetimeFractionalSeconds\Core\Database\ConnectionPool::class
    ];

If you use another extension which does the same, you have to handle this yourself.
