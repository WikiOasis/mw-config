<?php

$wgMainCacheType = 'redis';
$wgMessageCacheType = 'redis';
$wgCacheDirectory = '/var/www/mediawiki/cache/{$wgDBname}';

$wgParserCacheType = 'redis';

$wgLanguageConverterCacheType = CACHE_ACCEL;

$wgQueryCacheLimit = 5000;

// 15 days
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
}

$wgUseGzip = true;

$wgParsoidCacheConfig = [
    'StashType' => null,
    // store for 24h
    'StashDuration' => 24 * 60 * 60,
    // cache all
    'CacheThresholdTime' => 0.5,
    'WarmParsoidParserCache' => true,
];

$wgManageWikiServers = [
      'mwtask11:80',
      'mw11:80',
      'mw12:80',
      'mw41:80',
      'mw42:80',
];
