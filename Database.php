<?php

$wgMiserMode = true;

$wgSQLMode = null;

// Technically it's clustering but we only end up using one custer
if ( class_exists( \Wikimedia\Rdbms\LBFactoryMulti::class ) ) {
    // Yoink the connection details from PrivateSettings.php
    $primaryServer = $wgDBservers[0] ?? [
        'host' => '10.0.1.103',
        'user' => $wgDBuser ?? null,
        'password' => $wgDBpassword ?? null,
        'type' => 'mysql',
        'flags' => DBO_DEFAULT,
    ];

    $wgDBtype     = $primaryServer['type'] ?? 'mysql';
    $wgDBuser     = $primaryServer['user'] ?? null;
    $wgDBpassword = $primaryServer['password'] ?? null;

    if ( php_uname( 'n' ) === 'staging11' ) {
        $wgDBserver = 'db12';

        // Staging database configuration — db12 (10.0.1.106) handles all sections
        $wgLBFactoryConf = [
            'class' => \Wikimedia\Rdbms\LBFactoryMulti::class,
            'secret' => $wgSecretKey,

            'sectionsByDB' => $wi->wikiDBClusters,

            'sectionLoads' => [
                'DEFAULT' => [
                    'db12' => 0,
                ],
                'c1' => [
                    'db12' => 0,
                ],
            ],

            'serverTemplate' => [
                'dbname' => $wgDBname,
                'user' => $wgDBuser,
                'password' => $wgDBpassword,
                'type' => 'mysql',
                'flags' => DBO_DEFAULT | ( MW_ENTRY_POINT === 'cli' ? DBO_DEBUG : 0 ),
                'variables' => [
                    'innodb_lock_wait_timeout' => 120,
                ],
            ],

            'hostsByName' => [
                'db12' => 'db12',
            ],

            'externalLoads' => [
                'echo' => [
                    'db12' => 0,
                ],
            ],

            'readOnlyBySection' => [
                // 'DEFAULT' => 'Maintenance is in progress. Please try again in a few minutes.',
                // 'c1' => 'Maintenance is in progress. Please try again in a few minutes.',
            ],
        ];
    } else {
        $wgDBserver = $primaryServer['host'];

        // Production database configuration
        $wgLBFactoryConf = [
            'class' => \Wikimedia\Rdbms\LBFactoryMulti::class,
            'secret' => $wgSecretKey,

            'sectionsByDB' => $wi->wikiDBClusters,

            'sectionLoads' => [
                'DEFAULT' => [
                    'db11' => 0,
                ],
                'c1' => [
                    'db11' => 0,
                ],
            ],

            'serverTemplate' => [
                'dbname' => $wgDBname,
                'user' => $wgDBuser,
                'password' => $wgDBpassword,
                'type' => 'mysql',
                'flags' => DBO_DEFAULT | ( MW_ENTRY_POINT === 'cli' ? DBO_DEBUG : 0 ),
                'variables' => [
                    'innodb_lock_wait_timeout' => 120,
                ],
            ],

            'hostsByName' => [
                'db11' => $primaryServer['host'],
            ],

            'externalLoads' => [
                'echo' => [
                    'db11' => 0,
                ],
            ],

            'readOnlyBySection' => [
                // 'DEFAULT' => 'Maintenance is in progress. Please try again in a few minutes.',
                // 'c1' => 'Maintenance is in progress. Please try again in a few minutes.',
            ],
        ];
    }
}