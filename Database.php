<?php

$wgMiserMode = true;

$wgSQLMode = null;

// Technically it's clustering but we only end up using one custer
if ( class_exists( \Wikimedia\Rdbms\LBFactoryMulti::class ) ) {
    // Yoink the connection details from PrivateSettings.php
    $primaryServer = $wgDBservers[0] ?? [
        'host' => 'localhost',
        'user' => $wgDBuser ?? null,
        'password' => $wgDBpassword ?? null,
        'type' => 'mysql',
        'flags' => DBO_DEFAULT,
    ];

    $wgDBserver   = $primaryServer['host'];
    $wgDBtype     = $primaryServer['type'] ?? 'mysql';
    $wgDBuser     = $primaryServer['user'] ?? null;
    $wgDBpassword = $primaryServer['password'] ?? null;

    $wgLBFactoryConf = [
        'class' => \Wikimedia\Rdbms\LBFactoryMulti::class,
        'secret' => $wgSecretKey,

        'sectionsByDB' => $wi->wikiDBClusters,

        'sectionLoads' => [
            'DEFAULT' => [
                'db1' => 0,
            ],
            'c1' => [
                'db1' => 0,
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
            'db1' => $primaryServer['host'],
        ],

        'externalLoads' => [
            'echo' => [
                'db1' => 0,
            ],
        ],

        'readOnlyBySection' => [
            // 'DEFAULT' => 'Maintenance is in progress. Please try again in a few minutes.',
            // 'c1' => 'Maintenance is in progress. Please try again in a few minutes.',
        ],
    ];
}
