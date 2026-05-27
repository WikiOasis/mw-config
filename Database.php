<?php

$wgMiserMode = true;

$wgSQLMode = null;

// Technically it's clustering but we only end up using one cluster
if ( class_exists( \Wikimedia\Rdbms\LBFactoryMulti::class ) ) {
    // Yoink the connection details from PrivateSettings.php
    $primaryServer = $wgDBservers[0] ?? [
        'host' => 'db-c1-us-east-021',
        'user' => $wgDBuser ?? null,
        'password' => $wgDBpassword ?? null,
        'type' => 'mysql',
        'flags' => DBO_DEFAULT,
    ];

    $wgDBtype     = $primaryServer['type'] ?? 'mysql';
    $wgDBuser     = $primaryServer['user'] ?? null;
    $wgDBpassword = $primaryServer['password'] ?? null;
    $wgDBserver   = $primaryServer['host'];

    $wgLBFactoryConf = [
        'class' => \Wikimedia\Rdbms\LBFactoryMulti::class,
        'secret' => $wgSecretKey,

        'sectionsByDB' => $wi->wikiDBClusters,

        'sectionLoads' => [
            'DEFAULT' => [
                'db-c1-us-east-021' => 0,
            ],
            'c1' => [
                'db-c1-us-east-021' => 0,
            ],
        ],

        'serverTemplate' => [
            'dbname' => $wgDBname,
            'user' => $wgDBuser,
            'password' => $wgDBpassword,
            'type' => 'mysql',
            'flags' => DBO_DEFAULT,
            'variables' => [
                'innodb_lock_wait_timeout' => 120,
            ],
        ],

        'hostsByName' => [
            'db-c1-us-east-021' => $primaryServer['host'],
            'db-pc-us-east-011' => 'db-pc-us-east-011',
        ],

        'externalLoads' => [
            'echo' => [
                'db-c1-us-east-021' => 0,
            ],
            'pc1' => [
                'db-pc-us-east-011' => 0,
            ],
        ],

        'templateOverridesByCluster' => [
            'pc1' => [
                'dbname' => 'parsercache',
            ],
        ],

        'readOnlyBySection' => [
            'DEFAULT' => 'Maintenance is in progress. Please try again in a few minutes.',
            'c1' => 'Maintenance is in progress. Please try again in a few minutes.',
        ],
    ];
}

$wgDatabaseClustersMaintenance = ['c1'];