<?php

$EM_CONF['datetime_fractional_seconds'] = [
    'title' => 'DATETIME with fractional seconds',
    'description' => 'A TYPO3 custom Doctrine type for DATETIME with fractional seconds support.',
    'author' => 'Wolfgang Klinger',
    'author_email' => 'wolfgang@wazum.com',
    'state' => 'stable',
    'author_company' => 'wazum',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.13-11.9.99',
        ],
    ],
];
