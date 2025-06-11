<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Insurance Premium',
    'description' => 'This extension show premium value based on user age.',
    'category' => 'plugin',
    'author' => 'Zohaib',
    'author_email' => 'info@zohaib.dev',
    'state' => 'alpha',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-13.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
