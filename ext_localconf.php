<?php

declare(strict_types=1);

(static function (): void {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Database\ConnectionPool::class] = [
        'className' => \Wazum\DatetimeFractionalSeconds\Core\Database\ConnectionPool::class,
    ];
})();
