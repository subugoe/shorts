<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Short URL',
    'description' => 'URL Verkuerzer auf Basis von RealURL',
    'category' => 'plugin',
    'author' => 'Ingo Pfennigstorf',
    'author_email' => 'pfennigstorf@sub.uni-goettingen.de',
    'author_company' => 'Goettingen State and University Library, Germany http://www.sub.uni-goettingen.de',
    'shy' => '',
    'dependencies' => 'pagepath',
    'conflicts' => '',
    'priority' => '',
    'module' => '',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 0,
    'lockType' => '',
    'version' => '2.1.0',
    'constraints' => [
        'depends' => [
            'typo3' => '6.2.0-7.99.99',
            'pagepath' => '',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'suggests' => [],
];
