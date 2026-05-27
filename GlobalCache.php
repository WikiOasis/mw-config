<?php

$wgMainCacheType = 'redis';
$wgMessageCacheType = 'redis';
$wgCacheDirectory = '/var/www/mediawiki/cache/{$wgDBname}';

// ParserCache: write to Redis first (fast, memory-bounded via volatile-lru eviction),
// then replicate to SQL (durable, full TTL). Reads check Redis first, fall back to SQL.
// MultiWriteBagOStuff sends the same TTL to both backends; configure Redis with
// maxmemory-policy: volatile-lru so it evicts parser cache entries under memory pressure
// while the SQL backend retains them for the full $wgParserCacheExpireTime.
$wgObjectCaches['parsercache-multiwrite'] = [
    'class' => MultiWriteBagOStuff::class,
    'caches' => [
//        0 => $wgObjectCaches['redis'],
        0 => [
            'class' => SqlBagOStuff::class,
            'cluster' => 'pc1',
            'dbDomain' => 'parsercache',
            'purgePeriod' => 0,
            'tableName' => 'objectcache',
            'reportDupes' => false,
        ],
    ],
];

$wgParserCacheType = 'parsercache-multiwrite';

$wgLanguageConverterCacheType = CACHE_ACCEL;

$wgQueryCacheLimit = 5000;

// 15 days — SQL backend retains for the full duration; Redis evicts earlier via LRU
$wgParserCacheExpireTime = 86400 * 15;

// 10 days
$wgDiscussionToolsTalkPageParserCacheExpiry = 86400 * 10;

// 3 days
$wgRevisionCacheExpiry = 86400 * 3;

// 1 day
$wgObjectCacheSessionExpiry = 86400;

// 7 days
$wgDLPMaxCacheTime = 604800;

$wgDLPQueryCacheTime = 120;
$wgDplSettings['queryCacheTime'] = 120;

$wgSearchSuggestCacheExpiry = 10800;

if ( !$wmgSharedDomainPathPrefix ) {
    $wgEnableSidebarCache = true;
}

$wgUseLocalMessageCache = true;
$wgInvalidateCacheOnLocalSettingsChange = false;

$wgResourceLoaderUseObjectCacheForDeps = true;

$wgCdnMatchParameterOrder = false;

if ( PHP_SAPI === 'cli' ) {
        // APC not available in CLI mode
        $wgLanguageConverterCacheType = CACHE_NONE;

        // Suppress DEBUG/INFO log noise in CLI scripts; errors and warnings still reach stderr
        $wgMWLoggerDefaultSpi = [
            'class' => \MediaWiki\Logger\MonologSpi::class,
            'args' => [[
                'loggers' => [
                    '@default' => [ 'handlers' => [ 'stderr' ] ],
                ],
                'handlers' => [
                    'stderr' => [
                        'class' => \Monolog\Handler\StreamHandler::class,
                        'args'  => [ 'php://stderr', \Monolog\Logger::ERROR ],
                    ],
                ],
            ]],
        ];
}

$wgUseGzip = true;

$wgParsoidCacheConfig = [
    'StashType' => null,
    // store for 24h
    'StashDuration' => 24 * 60 * 60,
    // cache all
    'CacheThresholdTime' => 0.0,
    'WarmParsoidParserCache' => true,
];

$wgManageWikiServers = [
      'task-us-east-011:80',
      'mw-us-east-011:80',
      'mw-us-east-012:80',
      'mw-us-east-021:80',
      'mw-us-east-022:80',
];
