<?php

$EM_CONF['datetime_fractional_seconds'] = [
    'title' => 'DATETIME with fractional seconds',
    'description' => 'A TYPO3 custom Doctrine type for DATETIME with fractional seconds support.',
    'author' => 'Wolfgang Klinger',
    'author_email' => 'wolfgang@wazum.com',
    'state' => 'stable',
    'author_company' => 'wazum',
    'version' => '1.3.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-13.9.99',
        ],
    ],
];
