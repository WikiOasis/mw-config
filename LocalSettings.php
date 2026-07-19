<?php

use MediaWiki\MediaWikiServices;

/**
 * LocalSettings.php
 * Production LocalSettings file for WikiOasis
 * This file is included by the main LocalSettings.php file
 *
 * @var string $IP
 * @var mixed $wgConf
 */


if ( !defined( 'MEDIAWIKI' ) ) {
    die( 'Not an entry point.' );
}

if ( !defined( 'CACHE_MEMCACHED' ) ) {
    die( 'Not an entry point.' );
}

if ( !defined( 'MW_ENTRY_POINT' ) ) {
    die( 'Not an entry point.' );
}

// for debugging
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR);
#error_reporting(E_ALL);
ini_set( 'display_errors', E_ALL);
ini_set( 'xdebug.var_display_max_children', - 1 );
ini_set( 'xdebug.var_display_max_data', - 1 );
ini_set( 'xdebug.var_display_max_depth', - 1 );

// Raise the per-request memory ceiling above the php.ini default of 128M.
// MediaWiki only ever raises (never lowers) this during Setup; 128M is too
// low for the number of extensions loaded here and causes OOM fatals on
// heavy page parses.
ini_set( 'memory_limit', '256M' );
$wgMemoryLimit = '256M';

require_once "$IP/config/PrivateSettings.php";

$wgConf->suffixes = [ 'wiki' ];

$wgDBtype = "mysql";

$wgDBprefix = "";
$wgDBssl = false;

$wgDiff3 = "/usr/bin/diff3";
if ( php_uname( 'n' ) === 'staging11' ) {
    $wgVirtualDomainsMapping['virtual-centralauth'] = ['db' => 'wikidbbeta'];
    $wgVirtualDomainsMapping['virtual-checkuser-global'] = ['db' => 'wikidbbeta'];
    $wgVirtualDomainsMapping['virtual-createwiki'] = ['db' => 'wikidbbeta'];
    $wgVirtualDomainsMapping['virtual-createwiki-central'] = ['db' => 'metawikibeta'];
    $wgVirtualDomainsMapping['virtual-globalblocking'] = ['db' => 'wikidbbeta'];
    $wgVirtualDomainsMapping['virtual-managewiki'] = ['db' => 'wikidbbeta'];
    $wgVirtualDomainsMapping['virtual-oathauth'] = ['db' => 'wikidbbeta'];
    $wgVirtualDomainsMapping['virtual-LoginNotify'] = ['db' => 'wikidbbeta'];
    $wgVirtualDomainsMapping['virtual-importdump'] = ['db' => 'metawikibeta'];
    $wgVirtualDomainsMapping['virtual-requestcustomdomain'] = ['db' => 'metawikibeta'];
    $wgVirtualDomainsMapping['virtual-interwiki'] = ['db' => 'metawikibeta'];
    $wgVirtualDomainsMapping['virtual-interwiki-interlanguage'] = ['db' => 'metawikibeta'];
} else {
    $wgVirtualDomainsMapping['virtual-centralauth'] = ['db' => 'wikidb'];
    $wgVirtualDomainsMapping['virtual-checkuser-global'] = ['db' => 'wikidb'];
    $wgVirtualDomainsMapping['virtual-createwiki'] = ['db' => 'wikidb'];
    $wgVirtualDomainsMapping['virtual-createwiki-central'] = ['db' => 'metawiki'];
    $wgVirtualDomainsMapping['virtual-globalblocking'] = ['db' => 'wikidb'];
    $wgVirtualDomainsMapping['virtual-managewiki'] = ['db' => 'wikidb'];
    $wgVirtualDomainsMapping['virtual-oathauth'] = ['db' => 'wikidb'];
    $wgVirtualDomainsMapping['virtual-LoginNotify'] = ['db' => 'wikidb'];
    $wgVirtualDomainsMapping['virtual-importdump'] = ['db' => 'metawiki'];
    $wgVirtualDomainsMapping['virtual-requestcustomdomain'] = ['db' => 'metawiki'];
    $wgVirtualDomainsMapping['virtual-interwiki'] = ['db' => 'metawiki'];
    $wgVirtualDomainsMapping['virtual-interwiki-interlanguage'] = ['db' => 'metawiki'];
}

$wgDebugLogGroups['MirahezeFunctions'] = "/var/log/mediawiki/mf.log";
require_once "$IP/config/MirahezeFunctions.php";
require_once "$IP/config/GlobalExtensions.php";

$wi = new MirahezeFunctions();
// $wgReadOnly = ( PHP_SAPI === 'cli' ) ? null : 'This wiki is currently being upgraded to a newer software version. Please check back in a couple of hours.';

$wmgSharedDomainPathPrefix = '';

if ( ( $_SERVER['HTTP_HOST'] ?? '' ) === $wi->getSharedDomain()
    || getenv( 'MW_USE_SHARED_DOMAIN' )
) {
    $wgLoadScript = "{$wi->server}/w/load.php";
    $wmgSharedDomainPathPrefix = "/$wgDBname";

    $wgCanonicalServer = 'https://' . $wi->getSharedDomain();

    $wgUseSiteCss = false;
    $wgUseSiteJs = false;
}

$wgScriptPath = $wmgSharedDomainPathPrefix ?: '/w';
$wgScript = "$wgScriptPath/index.php";

$wgResourceBasePath = ( $wmgSharedDomainPathPrefix ?: '' ) . '/versions/' . $wi->version;
$wgExtensionAssetsPath = "$wgResourceBasePath/extensions";
$wgStylePath = "$wgResourceBasePath/skins";
$wgLocalStylePath = $wgStylePath;

$wgConf->settings += [
    'wgAuthenticationTokenVersion' => [
        'default' => '1',
    ],
    // ==================
    // MAINTENANCE THINGS
    // ==================
    // make sure we don't have any jobs in the queue!
    'wgJobRunRate' => [
        'default' => 0,
    ],
    'wgShowExceptionDetails' => [
        'default' => true,
    ],
    //'wgReadOnly' => [
    //    'default' => false,
    //    'default' => 'MediaWiki upgrade. Check for updates at status.wikioasis.org',
    //],
    'wgDisableSearchUpdate' => [
        'default' => false,
        'heroeswiki' => true,
    ],
    'wgCreateWikiDatabaseClusters' => [
        'default' => [
            'c1' => 'c1',
        ],
    ],
    'wgCreateWikiContainers' => [
        'default' => [
            'avatars' => 'public-private',
            'awards' => 'public-private',
            'local-public' => 'public-private',
            'local-thumb' => 'public-private',
            'local-transcoded' => 'public-private',
            'local-temp' => 'private',
            'local-deleted' => 'private',
            'dumps-backup' => 'public-private',
            'phonos-render' => 'public-private',
            'timeline-render' => 'public-private',
            'upv2avatars' => 'public-private',
        ],
    ],

    // =============
    // CORE SETTINGS
    // =============

    // limits
    'wgMaxArticleSize' => [
        'default' => 1024 * 2,
        'projectherzlwiki' => 1024 * 48,
        'smowiki' => 1024 * 10,
    ],
    'wgAPIMaxResultSize' => [
        'default' => 1024 * 2 * 2 * 1024,
        'projectherzlwiki' => 1024 * 48 * 2 * 1024,
    ],
    'wgHTTPTimeout' => [
        'default' => 3,
    ],

    // mail
    'wgEnableEmail' => [
        'default' => true,
    ],
    'wgEnableUserEmail' => [
        'default' => true,
    ],
    'wgEmergencyContact' => [
        'default' => '',
    ],
    'wgPasswordSender' => [
        'default' => 'noreply@wikioasis.org',
    ],
    'wgEnotifUserTalk' => [
        'default' => true,
    ],
    'wgEnotifWatchlist' => [
        'default' => true,
    ],
    'wgEmailAuthentication' => [
        'default' => true,
    ],

    // file uploads
    'wgEnableUploads' => [
        'default' => true,
    ],
    'wgGenerateThumbnailOnParse' => [
        'default' => true,
    ],
    'wgThumbnailSteps' => [
	'default' => [20, 40, 60, 120, 250, 330, 500, 960, 1280, 1920, 3840],
    ],
    'wgThumbnailSetpsRatio' => [
	'default' => 1,
    ],
    'wgMaxUploadSize' => [
        'default' => 1024 * 1024 * 128,
    ],
    'wgAllowCopyUploads' => [
        'default' => false,
    ],
    'wgCopyUploadsFromSpecialUpload' => [
        'default' => false,
    ],
    'wmgSharedUploadDBname' => [
        'default' => false,
    ],
    'wgFileExtensions' => [
        'default' => [
            'djvu',
            'gif',
            'ico',
            'jpg',
            'jpeg',
            'ogg',
            'pdf',
            'png',
            'svg',
            'webp',
        ],
    ],
    'wgSVGNativeRendering' => [
        'default' => true,
    ],

    // for nginx
    'wgArticlePath' => [
        'default' => '/wiki/$1',
    ],
    'wgUsePathInfo' => [
        'default' => true,
    ],
    'wgEnableCanonicalServerLink' => [
        'default' => true,
    ],

    // for private wikis
    'wgWhitelistReadRegexp' => [
        'default' => [
            '/^(特別|Special):CentralAutoLogin.*/',
            '/^(特別|Special):CentralLogin.*/',
        ],
    ],
    'wgWhitelistRead' => [
        'default' => [
            'Special:UserLogin',
            'Special:UserLogout',
            'Special:CreateAccount',
            '特別:ログイン',
            '特別:ログアウト',
            '特別:アカウント作成',
            'Main Page',
            'メインページ',
        ],
    ],

    // Prevent mh from being treated as an interlanguage link (T11615)
    'wgExtraLanguageNames' => [
        'default' => [
            'mh' => '',
            'wo' => '',
            'sw' => '',
        ],
    ],

    // Footer icons
    '+wgFooterIcons' => [
        'songnguxyzwiki' => [
            'hostedby' => [
                'songnguxyz' => [
                    'src' => '//songnguxyz.wikioasis.org/img_auth.php/5/58/Footer.SN.xyz.svg',
                    'url' => 'https://songngu.xyz',
                    'alt' => 'Dự án được bảo quản bởi SongNgư.xyz',
                    "height" => "32",
                    "width" => "200",
                ],
            ],
            'poweredby' => [
                'mediawiki' => [
                    'src' => '//songnguxyz.wikioasis.org/img_auth.php/9/9b/MediaWiki.svg',
                    'url' => '//www.mediawiki.org',
                    'alt' => 'Xây dựng trên MediaWiki',
                    'height' => "60",
                    'width' => "185",
                ],
            ],
            'copyright' => [
                'copyright' => [
                    'src' => '//songnguxyz.wikioasis.org/img_auth.php/8/89/ARR.svg',
                    'alt' => 'Toàn quyền được bảo lưu',
                    'height' => "50",
                    'width' => "50",
                ],
            ],
        ],
        'solarpunkwiki' => [
            'wopartner' => [
                'partner' => [
                    'src' => '//cdn.wikioasis.org/metawiki/5/57/Wikioasis_Partner_Footer.svg',
                    'alt' => 'WikiOasis Partner Icon',
                    'url' => '//meta.wikioasis.org/wiki/WikiOasis_Partner_Program',
                ],
            ],
        ],
        'wikicordwiki' => [
            'wopartner' => [
                'partner' => [
                    'src' => '//cdn.wikioasis.org/metawiki/5/57/Wikioasis_Partner_Footer.svg',
                    'alt' => 'WikiOasis Partner Icon',
                    'url' => '//meta.wikioasis.org/wiki/WikiOasis_Partner_Program',
                ],
            ],
        ],
        'aeronauticawiki' => [
            'wopartner' => [
                'partner' => [
                    'src' => '//cdn.wikioasis.org/metawiki/5/57/Wikioasis_Partner_Footer.svg',
                    'alt' => 'WikiOasis Partner Icon',
                    'url' => '//meta.wikioasis.org/wiki/WikiOasis_Partner_Program',
                ],
            ],
        ],
    ],
    // copyright
    'wgRightsPage' => [
        'default' => '',
    ],
    'wgRightsUrl' => [
        'default' => '',
    ],
    'wmgWikiLicense' => [
        'default' => 'cc-by-sa',
    ],

    // ImageMagick
    'wgUseImageMagick' => [
        'default' => true,
    ],
    'wgImageMagickConvertCommand' => [
        'default' => '/usr/bin/convert',
    ],

    // database
    'wgSharedTables' => [
        'default' => [],
    ],

    // delete revisions
    'wgDeleteRevisionsLimit' => [
        'default' => 1000,
    ],

    // preferences
    'wgHiddenPrefs' => [
        'default' => [ 'realname' ],
    ],
    'wgDefaultSkin' => [
        'default' => 'citizen',
    ],
    'wgDefaultMobileSkin' => [
        'default' => 'citizen',
    ],
    '+wgDefaultUserOptions' => [
        'default' => [
            'visualeditor-newwikitext' => 0,
        ],
    ],
    // styling
    'wgAllowUserCss' => [
        'default' => true,
    ],
    'wgAllowUserJs' => [
        'default' => true,
    ],
    'wgIcon' => [
        'default' => false,
    ],
    'wgWordmark' => [
        'default' => false,
    ],
    'wgWordmarkHeight' => [
        'default' => 18,
    ],
    'wgWordmarkWidth' => [
        'default' => 116,
    ],
    'wgMaxTocLevel' => [
        'default' => 999,
    ],
    // RecentChanges
    'wgFeedLimit' => [
        'default' => 50,
    ],
    'wgRCMaxAge' => [
        'default' => 180 * 24 * 3600,
    ],
    'wgRCLinkDays' => [
        'default' => [ 1, 3, 7, 14, 30 ],
    ],
    'wgRCLinkLimits' => [
        'default' => [ 50, 100, 250, 500 ],
    ],
    'wgUseRCPatrol' => [
        'default' => true,
    ],
    // parser
    'wgParserMigrationEnableParsoidArticlePages' => [
	    'default' => false,
    ],
    'wgParserMigrationEnableParsoidDiscussionTools' => [
	    'default' => false,
    ],
    // for Cloudflare/Varnish
    'wgUseCdn' => [
        'default' => true,
    ],
    'wgCdnServersNoPurge' => [
        'default' => [
	    // in house cp servers
	    "10.0.1.2", // proxy-us-east-011
	    "10.0.2.2", // proxy-us-east-021
            // IPv4 addresses
	    "127.0.0.1",
            "103.21.244.0/22",
            "103.22.200.0/22",
            "103.31.4.0/22",
            "104.16.0.0/13",
            "104.24.0.0/14",
            "108.162.192.0/18",
            "131.0.72.0/22",
            "141.101.64.0/18",
            "162.158.0.0/15",
            "172.64.0.0/13",
            "173.245.48.0/20",
            "188.114.96.0/20",
            "190.93.240.0/20",
            "197.234.240.0/22",
            "198.41.128.0/17",

            // IPv6 addresses
            "2400:cb00::/32",
            "2606:4700::/32",
            "2803:f800::/32",
            "2405:b500::/32",
            "2405:8100::/32",
            "2a06:98c0::/29",
        ],
    ],

    // template
    'wgEnableScaryTranscluding' => [
        'default' => true,
    ],

    // permissions
    '+wgRevokePermissions' => [
        '+ext-MediaWikiChat' => [
            'blockedfromchat' => [
                'chat' => true,
            ],
        ],
    ],
    'wgImplicitGroups' => [
        'default' => [
            '*',
            'user',
            'autoconfirmed'
        ],
    ],

    // url
    'wgMainPageIsDomainRoot' => [
        'default' => false,
        'metawiki' => true,
    ],

    // ==================
    // TECHNICAL SETTINGS
    // ==================

    // Cache
    'wgExtensionEntryPointListFiles' => [
        'default' => [
            "/srv/mediawiki/config/extension-list"
        ],
    ],

    // Performance tuning
    'wgCategoryPagingLimit' => [
        'default' => 200,
        'italianbrainrotwiki' => 50,
    ],


    // ==================
    // EXTENSION SETTINGS
    // ==================

    // AbuseFilter
    'wgAbuseFilterActions' => [
        'default' => [
            'block' => true,
            'blockautopromote' => true,
            'degroup' => true,
            'disallow' => true,
            'rangeblock' => false,
            'tag' => true,
            'throttle' => true,
            'warn' => true,
        ],
    ],
    'wgAbuseFilterCentralDB' => [
        'default' => 'metawiki',
        'beta' => 'metawikibeta',
    ],
    'wgAbuseFilterIsCentral' => [
        'default' => false,
        'metawiki' => true,
        'metawikibeta' => true,
    ],
    'wgAbuseFilterBlockDuration' => [
        'default' => 'indefinite',
    ],
    'wgAbuseFilterAnonBlockDuration' => [
        'default' => 2592000,
    ],
    'wgAbuseFilterNotifications' => [
        'default' => 'udp',
    ],
    'wgAbuseFilterLogPrivateDetailsAccess' => [
        'default' => true,
    ],
    'wgAbuseFilterPrivateDetailsForceReason' => [
        'default' => true,
    ],
    'wgAbuseFilterEmergencyDisableThreshold' => [
        'default' => [
            'default' => 0.05,
        ],
    ],
    'wgAbuseFilterEmergencyDisableCount' => [
        'default' => [
            'default' => 2,
        ],
    ],

    // CentralAuth
    'wgCentralAuthAutoCreateWikis' => [
        'default' => [
            'loginwiki',
            'metawiki',
        ],
	'beta' => [
	    'metawikibeta',
	],
    ],
    'wgCentralAuthAutoMigrate' => [
        'default' => true,
    ],
    'wgCentralAuthAutoMigrateNonGlobalAccounts' => [
        'default' => true,
    ],
    'wgCentralAuthCookies' => [
        'default' => true,
    ],
    'wgCentralAuthCookiePrefix' => [
        'default' => 'centralauth_',
    ],
    'wgCentralAuthDatabase' => [
        'default' => 'wikidb',
	'beta' => 'wikidbbeta',
    ],
    'wgCentralAuthEnableGlobalRenameRequest' => [
        'default' => true,
    ],
    'wgCentralAuthGlobalBlockInterwikiPrefix' => [
        'default' => 'meta',
    ],
    'wgCentralAuthLoginWiki' => [
        'default' => 'loginwiki',
    ],
    'wgCentralAuthOldNameAntiSpoofWiki' => [
        'default' => 'metawiki',
	'beta' => 'metawikibeta',
    ],
    'wgCentralAuthCentralWiki' => [
        'default' => 'metawiki',
        'beta' => 'metawikibeta',
    ],
    'wgCentralAuthPreventUnattached' => [
        'default' => true,
    ],
    'wgCentralAuthRestrictSharedDomain' => [
        'default' => true,
    ],
    'wgCentralAuthTokenCacheType' => [
        'default' => 'redis',
    ],
    'wgCookieSameSite' => [
        'default' => 'None',
    ],
    'wgUseSameSiteLegacyCookies' => [
        'default' => true,
    ],

    // CreateWiki
    'wgCreateWikiDatabase' => [
        'default' => 'wikidb',
 	'beta' => 'wikidbbeta',
    ],
    'wgCreateWikiUseJobQueue' => [
        'default' => true,
    ],
    'wgCreateWikiCategories' => [
        'default' => [
            'Art & Architecture' => 'artarc',
            'Automotive' => 'automotive',
            'Business & Finance' => 'businessfinance',
            'Community' => 'community',
            'Education' => 'education',
            'Electronics' => 'electronics',
            'Entertainment' => 'entertainment',
            'Fandom' => 'fandom',
            'Fantasy' => 'fantasy',
            'Gaming' => 'gaming',
            'Geography' => 'geography',
            'History' => 'history',
            'Humour/Satire' => 'humour',
            'Language/Linguistics' => 'langling',
            'Leisure' => 'leisure',
            'Literature/Writing' => 'literature',
            'Media/Journalism' => 'media',
            'Medicine/Medical' => 'medical',
            'Military/War' => 'military',
            'Music' => 'music',
            'Podcast' => 'podcast',
            'Politics' => 'politics',
            'Private' => 'private',
            'Religion' => 'religion',
            'Science' => 'science',
            'Software/Computing' => 'software',
            'Song Contest' => 'songcontest',
            'Sports' => 'sport',
            'Uncategorised' => 'uncategorised',
        ],
    ],
    'wgCreateWikiUseCategories' => [
        'default' => true,
    ],
    'wgCreateWikiSubdomain' => [
        'default' => 'wikioasis.org',
    ],
    'wgCreateWikiUseClosedWikis' => [
        'default' => true,
    ],
    'wgCreateWikiUseCustomDomains' => [
        'default' => true,
    ],
    'wgCreateWikiUseEchoNotifications' => [
        'default' => true,
    ],
    'wgCreateWikiUseExperimental' => [
        'default' => true,
    ],
    'wgCreateWikiUseInactiveWikis' => [
        'default' => true,
    ],
    'wgCreateWikiUsePrivateWikis' => [
        'default' => true,
    ],
    'wgCreateWikiCacheDirectory' => [
        'default' => '/srv/mediawiki/cw_cache',
    ],
    'wgCreateWikiCacheType' => [
        'default' => 'redis',
    ],
    'wgCreateWikiShowBiographicalOption' => [
		'default' => true,
    ],
    'wgCreateWikiDatabaseSuffix' => [
        'default' => 'wiki',
    ],
    'wgCreateWikiDisableRESTAPI' => [
        'default' => true,
        'metawiki' => false,
	'metawikibeta' => false,
    ],
    'wgCreateWikiGlobalWiki' => [
        'default' => 'metawiki',
	'beta' => 'metawikibeta',
    ],
    'wgCreateWikiEmailNotifications' => [
        'default' => true,
    ],
    'wgCreateWikiInactiveExemptReasonOptions' => [
        'default' => [
            'Wiki completed and made to be read' => 'comp',
            'Wiki made for time-based gathering' => 'tbg',
            'Wiki made to be read' => 'mtr',
            'Temporary exemption for exceptional hardship, see DPE' => 'temphardship',
            'Other, see DPE' => 'other',
        ],
    ],
    'wgCreateWikiCannedResponses' => [
        'default' => [
            'Approval reasons' => [
                'Approved' => 'Please ensure your wiki complies with the WikiOasis Content Policy at all times or it may be closed. Thank you for choosing WikiOasis!',
            ],
            'Decline reasons' => [
                'Obscene/Offensive Name/Subdomain' => 'We do not permit wikis with offensive names or subdomains. Please change the name or subdomain. Thank you.',
                'Vandalism/Trolling' => 'This wiki request is a product of vandalism or trolling.',
				'Content Policy: Illegal' => 'Declining per Content Policy provision: "Follow United States law." If you believe this was in error, please explain how your wiki will not violate the policy. Thank you.',
				'Content Policy: Problematic' => 'Declining per Content Policy provision: "Don\'t cause problems for WikiOasis." If you believe this was in error, please explain how your wiki will not violate the policy. Thank you.',
				'Content Policy: Hateful' => 'Declining per Content Policy provision: "No hate speech." If you believe this was in error, please explain how your wiki will not violate the policy. Thank you.',
				'Content Policy: Gratuitous NSFW' => 'Declining per Content Policy provision: "No gratuitous NSFW." If you believe this was in error, please explain how your wiki will not violate the policy. Thank you.',
				'Content Policy: Commercial' => 'Declining per Content Policy provision: "Don\'t use wikis as a tool for commercial promotion." If you believe this was in error, please explain how your wiki will not violate the policy. Thank you.',
				'Content Policy: File Hosting' => 'Declining per Content Policy provision: "Don\'t use wikis as file hosting services." If you believe this was in error, please explain how your wiki will not violate the policy. Thank you.',
				'Content Policy: Anarchy' => 'Declining per Content Policy provision: "No anarchy wikis." If you believe this was in error, please explain how your wiki will not violate the policy. Thank you.',
				'Content Policy: Duplicate' => 'Your wiki appears to duplicate the scope of an existing WikiOasis wiki, which is not allowed per the Content Policy. Please contribute to the existing wiki instead; if you believe this was in error, please explain how your wiki will not violate the policy. Thank you.',
            ],
            'On hold reasons' => [
                'More details needed' => 'Please describe your wiki and its topic in more detail.',
                'On hold pending response' => 'This request is on hold pending a response from you. Please see the "Request Comments" tab and reply to the questions asked by the reviewer. Thank you.',
                'On hold pending internal review' => 'This request has been placed on hold for internal review by another Steward. Thank you for your patience.',
            ],
        ],
    ],
    'wgCreateWikiDisallowedSubdomains' => [
        'default' => [
            'www',
            'ftp',
            'mail',
            'webmail',
            'admin',
            'administrator',
            'hostmaster',
            'webmaster',
            'abuse',
            'contact',
            'info',
            'privacy',
            'legal',
            'help',
            'support',
            'blog',
            'wiki',
            'wiki2',
            'forums',
            'forum',
            'phorge',
            'phabricator',
            'phab',
            'issues',
            'bugzilla',
            'issue-tracker',
        ]
    ],
    'wgCreateWikiStateDays' => [
        'default' => [
            'inactive' => 90,
            'closed' => 30,
            'removed' => 120,
            'deleted' => 30,
        ]
    ],
    'wgCreateWikiEnableManageInactiveWikis' => [
        'default' => true,
    ],
    'wgCreateWikiSQLFiles' => [
        'default' => [
            "$IP/sql/mysql/tables-generated.sql",
            "$IP/extensions/AbuseFilter/db_patches/mysql/tables-generated.sql",
            "$IP/extensions/AntiSpoof/sql/mysql/tables-generated.sql",
            "$IP/extensions/BetaFeatures/sql/tables-generated.sql",
            "$IP/extensions/CheckUser/schema/mysql/tables-generated.sql",
            "$IP/extensions/CentralNotice/sql/mysql/tables-generated.sql",
            "$IP/extensions/DataDump/sql/data_dump.sql",
            "$IP/extensions/Echo/sql/mysql/tables-generated.sql",
            "$IP/extensions/GlobalBlocking/sql/mysql/tables-generated-global_block_whitelist.sql",
            #"$IP/extensions/LoginNotify/sql/mysql/tables-generated.sql",
            "$IP/extensions/OATHAuth/sql/mysql/tables-generated.sql",
            "$IP/extensions/OAuth/schema/mysql/tables-generated.sql",
	        "$IP/extensions/MediaModeration/schema/mysql/tables-generated.sql",
            //"$IP/extensions/RottenLinks/sql/rottenlinks.sql",
            //"$IP/extensions/UrlShortener/schemas/tables-generated.sql",
        ],
    ],
    // CheckUser
	'wgCheckUserForceSummary' => [
        'default' => true,
    ],
	'wgCheckUserDisableCheckUserAPI' => [
		'default' => false,
	],
    'wgCheckUserLogLogins' => [
        'default' => true,
    ],
    'wgCheckUserSuggestedInvestigationsEnabled' => [
        'default' => true,
    ],

    // WebAuthn
    'wgWebAuthnLimitPasskeysToRoaming' => [
        'default' => true,
    ],

    // ManageWiki
    'wgManageWikiModulesEnabled' => [
        'default' => [
            'core' => true,
            'extensions' => true,
            'namespaces' => true,
            'permissions' => true,
            'settings' => true
        ],
    ],
    'wgManageWikiCacheDirectory' => [
        'default' => '/srv/mediawiki/cw_cache',
    ],
    'wgManageWikiUseCustomDomains' => [
        'default' => true,
    ],
    'wgManageWikiPermissionsAdditionalAddGroups' => [
        'default' => [
            'bureaucrat' => [
                  'temporary-account-viewer'
	    ],
        ],
    ],
    'wgManageWikiPermissionsAdditionalRights' => [
        'default' => [
            '*' => [
                'autocreateaccount' => true,
                'createaccount' => true,
                'editmyoptions' => true,
                'editmyprivateinfo' => true,
                'editmywatchlist' => true,
                'oathauth-enable' => true,
                'viewmyprivateinfo' => true,
                'writeapi' => true,
            ],
            'bureaucrat' => [
                'managewiki-core' => true,
                'managewiki-extensions' => true,
                'managewiki-namespaces' => true,
                'managewiki-permissions' => true,
                'managewiki-settings' => true,
                'managewiki-privacy' => true,
            ],
            'checkuser' => [
                'abusefilter-privatedetails' => true,
                'abusefilter-privatedetails-log' => true,
                'checkuser' => true,
                'checkuser-log' => true,
            ],
            'member' => [
                'read' => true,
            ],
            'steward' => [
                'userrights' => true,
            ],
            'suppress' => [
                'abusefilter-hidden-log' => true,
                'abusefilter-hide-log' => true,
                'browsearchive' => true,
                'deletedhistory' => true,
                'deletedtext' => true,
                'deletelogentry' => true,
                'deleterevision' => true,
                'hideuser' => true,
                'suppressionlog' => true,
                'suppressrevision' => true,
                'viewsuppressed' => true,
            ],
            'sysop' => [
                'abusefilter-access-protected-vars' => true,
                'abusefilter-log-detail' => true,
                'abusefilter-log-private' => true,
                'abusefilter-modify' => true,
                'abusefilter-modify-blocked-external-domains' => true,
                'abusefilter-modify-restricted' => true,
                'abusefilter-protected-vars-log' => true,
                'abusefilter-revert' => true,
                'abusefilter-view-private' => true,
                'adminlinks' => true,
                'apihighlimits' => true,
                'autoconfirmed' => true,
                'autopatrol' => true,
                'block' => true,
                'blockemail' => true,
                'browsearchive' => true,
                'centralauth-createlocal' => true,
                'centralnotice-admin' => true,
                'createaccount' => true,
                'cfcachepurge' => true,
                'delete' => true,
                'delete-dump' => true,
                'deletechangetags' => true,
                'deletelogentry' => true,
                'deleterevision' => true,
                'deletedhistory' => true,
                'deletedtext' => true,
                'edit' => true,
                'editinterface' => true,
                'editprotected' => true,
                'editsemiprotected' => true,
                'editsitecss' => true,
                'editsitejs' => true,
                'editsitejson' => true,
                'editusercss' => true,
                'edituserjs' => true,
                'edituserjson' => true,
                'generate-dump' => true,
                'globalblock-whitelist' => true,
                'import' => true,
                'importupload' => true,
                'interwiki' => true,
                'ipblock-exempt' => true,
                'managechangetags' => true,
                'markbotedits' => true,
                'mass-upload' => true,
                'masseditregex' => true,
                'massmessage' => true,
                'mergehistory' => true,
                'moderation' => true,
                'move' => true,
                'move-categorypages' => true,
                'move-rootuserpages' => true,
                'move-subpages' => true,
                'movefile' => true,
                'noratelimit' => true,
                'nuke' => true,
                'oathauth-disable-for-user' => true,
                'oathauth-verify-user' => true,
                'oathauth-view-log' => true,
                'override-antispoof' => true,
                'pagetranslation' => true,
                'patrol' => true,
                'patrolmarks' => true,
                'protect' => true,
                'read' => true,
                'replacetext' => true,
                'reupload' => true,
                'reupload-shared' => true,
                'rollback' => true,
                'setmentor' => true,
                'skip-moderation' => true,
                'skip-move-moderation' => true,
                'skipcaptcha' => true,
                'suppressredirect' => true,
                'tboverride' => true,
                'titleblacklistlog' => true,
                'transcode-reset' => true,
                'transcode-status' => true,
                'translate-import' => true,
                'translate-manage' => true,
                'undelete' => true,
                'unblockself' => true,
                'unwatchedpages' => true,
                'upload' => true,
                'upload_by_url' => true,
                'upwizcampaigns' => true,
                'urlshortener-create-url' => true,
                'urlshortener-manage-url' => true,
                'urlshortener-view-log' => true,
                'view-dump' => true,
            ],
            'user' => [
                'edit' => true,
                'mwoauthmanagemygrants' => true,
                'user' => true,
            ],
        ],
        '+metawiki' => [
            'checkuser' => [
                'abusefilter-privatedetails' => true,
                'abusefilter-privatedetails-log' => true,
                'checkuser' => true,
                'checkuser-log' => true,
            ],
            'confirmed' => [
                'mwoauthproposeconsumer' => true,
                'mwoauthupdateownconsumer' => true,
            ],
            'electionadmin' => [
                'securepoll-create-poll' => true,
            ],
            'global-sysop' => [
                'globalblock' => true,
                'centralauth-rename' => true,
				'createwiki' => true,
				'centralauth-lock' => true,
            ],
            'steward' => [
                'abusefilter-modify-global' => true,
                'centralauth-lock' => true,
                'centralauth-rename' => true,
                'centralauth-suppress' => true,
                'centralauth-unmerge' => true,
                'createwiki' => true,
                'createwiki-deleterequest' => true,
                'globalblock' => true,
                'globalgroupmembership' => true,
                'globalgrouppermissions' => true,
                'handle-import-request-interwiki' => true,
                'handle-import-requests' => true,
                'managewiki-core' => true,
                'managewiki-extensions' => true,
                'managewiki-namespaces' => true,
                'managewiki-permissions' => true,
                'managewiki-restricted' => true,
                'managewiki-settings' => true,
                'noratelimit' => true,
                'oathauth-verify-user' => true,
                'userrights' => true,
                'userrights-interwiki' => true,
                'view-private-import-requests' => true,
            ],
            'suppress' => [
                'createwiki-suppressrequest' => true,
                'createwiki-suppressionlog' => true,
            ],
            'tech' => [
                'createwiki' => true,
                'createwiki-deleterequest' => true,
                'globalgroupmembership' => true,
                'globalgrouppermissions' => true,
                'handle-import-request-interwiki' => true,
                'handle-import-requests' => true,
                'handle-custom-domain-requests' => true,
                'managewiki-core' => true,
                'managewiki-extensions' => true,
                'managewiki-namespaces' => true,
                'managewiki-permissions' => true,
                'managewiki-restricted' => true,
                'managewiki-settings' => true,
                'userrights' => true,
                'userrights-interwiki' => true,
                'view-private-import-requests' => true,
            ],
            'sysop' => [
                'interwiki' => true,
            ],
            'safety' => [
                'abusefilter-modify-global' => true,
                'abusefilter-modify-restricted' => true,
                'managewiki-core' => true,
                'managewiki-extensions' => true,
                'managewiki-namespaces' => true,
                'managewiki-permissions' => true,
                'managewiki-restricted' => true,
                'managewiki-settings' => true,
                'centralauth-lock' => true,
                'centralauth-rename' => true,
                'centralauth-merge' => true,
                'centralauth-createlocal' => true,
                'globalblock' => true,
                'globalgroupmembership' => true,
                'globalgrouppermissions' => true,
                'handle-pii' => true,
                'oathauth-disable-for-user' => true,
                'oathauth-verify-user' => true,
                'userrights' => true,
                'userrights-interwiki' => true,
                'view-private-import-requests' => true,
            ],
            'user' => [
                'request-import' => true,
                'request-custom-domain' => true,
                'requestwiki' => true,
            ],
        ],
    ],
    'wgManageWikiPermissionsAdditionalRemoveGroups' => [
        'default' => [
            'bureaucrat' => [
                  'temporary-account-viewer'
            ],
        ],
    ],
    'wgManageWikiPermissionsDisallowedRights' => [
        'default' => [
            'any' => [
                'abusefilter-hide-log',
                'abusefilter-hidden-log',
                'abusefilter-modify-global',
                'abusefilter-private',
                'abusefilter-private-log',
                'abusefilter-privatedetails',
                'abusefilter-privatedetails-log',
                'aft-oversighter',
                'autocreateaccount',
                'bigdelete',
                'centralauth-createlocal',
                'centralauth-lock',
                'centralauth-suppress',
                'centralauth-rename',
                'centralauth-unmerge',
                'checkuser',
                'checkuser-log',
                'createwiki',
                'createwiki-deleterequest',
                'createwiki-suppressionlog',
                'createwiki-suppressrequest',
                'editincidents',
                'editothersprofiles-private',
                'flow-suppress',
                'generate-random-hash',
                'globalblock',
                'globalblock-exempt',
                'globalgroupmembership',
                'globalgrouppermissions',
                'handle-import-request-interwiki',
                'handle-import-requests',
                'handle-pii',
                'hideuser',
                'investigate',
                'import',
                'ipinfo',
                'ipinfo-view-basic',
                'ipinfo-view-full',
                'ipinfo-view-log',
                'managewiki-restricted',
                'managewiki-editdefault',
                'moderation-checkuser',
                'mwoauthmanageconsumer',
                'mwoauthmanagemygrants',
                'mwoauthsuppress',
                'mwoauthviewprivate',
                'mwoauthviewsuppressed',
                'oathauth-api-all',
                'oathauth-enable',
                'oathauth-disable-for-user',
                'oathauth-recover-for-user',
                'oathauth-verify-user',
                'oathauth-view-log',
                'renameuser',
                'request-import',
                'requestwiki',
                'siteadmin',
                'securepoll-view-voter-pii',
                'smw-admin',
                'smw-patternedit',
                'smw-viewjobqueuewatchlist',
                'stopforumspam',
                'suppressionlog',
                'suppressrevision',
                'themedesigner',
                'titleblacklistlog',
                'updatepoints',
                'userrights',
                'userrights-interwiki',
                'view-private-import-requests',
                'viewglobalprivatefiles',
                'viewpmlog',
                'viewsuppressed',
                'writeapi',
            ],
            'user' => [
                'autoconfirmed',
                'noratelimit',
                'skipcaptcha',
                'managewiki-core',
                'managewiki-extensions',
                'managewiki-namespaces',
                'managewiki-permissions',
                'managewiki-settings',
                'globalblock-whitelist',
                'ipblock-exempt',
                'interwiki',
            ],
            '*' => [
                'read',
                'skipcaptcha',
                'torunblocked',
                'centralauth-merge',
                'generate-dump',
                'editsitecss',
                'editsitejson',
                'editsitejs',
                'editusercss',
                'edituserjson',
                'edituserjs',
                'editmyoptions',
                'editmyprivateinfo',
                'editmywatchlist',
                'globalblock-whitelist',
                'interwiki',
                'ipblock-exempt',
                'viewmyprivateinfo',
                'viewmywatchlist',
                'managewiki-core',
                'managewiki-extensions',
                'managewiki-namespaces',
                'managewiki-permissions',
                'managewiki-settings',
                'noratelimit',
                'autoconfirmed',
            ],
        ],
    ],
    'wgManageWikiPermissionsDisallowedGroups' => [
        'default' => [
            'checkuser',
            'smwadministrator',
            'oversight',
            'steward',
            'suppress',
            'tech',
            'safety',
        ],
    ],
    'wgManageWikiPermissionsDefaultPrivateGroup' => [
        'default' => 'member',
    ],
    'wgManageWikiExtensionsDefault' => [
        'default' => [
            'categorytree',
            'cite',
            'citethispage',
            'codeeditor',
            'codemirror',
            'darkmode',
            'globaluserpage',
            'minervaneue',
            'mobilefrontend',
            'syntaxhighlight_geshi',
            'textextracts',
            'urlshortener',
            'wikiseo',
        ],
    ],
    // MediaModeration
    'wgMediaModerationDeveloperMode' => [
	    'default' => false,
    ],
    'wgMediaModerationFrom' => [
	    'default' => 'noreply@wikioasis.org',
    ],
    'wgMediaModerationRecipientList' => [
	    'default' => [
	    	'safety@wikioasis.org',
	    ],
    ],
    // GlobalBlocking & GlobalPreferences & GlobalUserPage & GlobalCssJs & GlobalUsage
    'wgGlobalBlockingDatabase' => [
        'default' => 'wikidb',
	'beta' => 'wikidbbeta',
    ],
    'wgGlobalPreferencesDB' => [
        'default' => 'wikidb',
	'beta' => 'wikidbbeta',
    ],
	'wgGlobalUsageSharedRepoWiki' => [
		'govnpcommonsbetawiki' => 'govnpcommonsbetawiki',
		'govnpdevwiki' => 'govnpcommonsbetawiki',
	    'govnpbetawiki' => 'govnpcommonsbetawiki',
	    'govnpediabetawiki' => 'govnpcommonsbetawiki',
	],
	'wgGlobalUsagePurgeBacklinks' => [
		'default' => true,
	],
    'wgGlobalUserPageAPIUrl' => [
        'default' => 'https://meta.wikioasis.org/w/api.php',
    ],
    'wgGlobalUserPageDBname' => [
        'default' => 'metawiki',
        'beta' => 'metawikibeta',
    ],
    'wgUseGlobalSiteCssJs' => [
        'default' => true,
    ],
    '+wgResourceLoaderSources' => [
        'default' => [
            'metawiki' => [
                'apiScript' => '//meta.wikioasis.org/w/api.php',
                'loadScript' => '//meta.wikioasis.org/w/load.php',
            ],
        ],
	'beta' => [
	    'metawikibeta' => [
		'apiScript' => '//meta.betaoasis.xyz/w/api.php',
		'loadScript' => '//meta.betaoasis.xyz/w/load.php',
	    ],
	],
    ],
    'wgGlobalCssJsConfig' => [
        'default' => [
            'wiki' => 'metawiki',
            'source' => 'metawiki',
        ],
	'beta' => [
	    'wiki' => 'metawikibeta',
	    'source' => 'metawikibeta',
	],
    ],

    // Temporary accounts
    'wgAutoCreateTempUser' => [
        'default' => [
            'enabled' => true,
            'known' => false,
            'actions' => ['edit'],
            'genPattern' => '~$1',
            'matchPattern' => null,
            'reservedPattern' => '~$1',
            'serialProvider' => [
                'type' => 'centralauth',
                'useYear' => true,
            ],
            'serialMapping' => [
                'type' => 'readable-numeric',
            ],
            'expireAfterDays' => 90,
            'notifyBeforeExpirationDays' => 10,
        ],
    ],

    // OAuth
    'wgMWOAuthCentralWiki' => [
        'default' => 'metawiki',
	'beta' => 'metawikibeta',
    ],
    'wgOAuth2GrantExpirationInterval' => [
        'default' => 'PT4H',
    ],
    'wgOAuth2RefreshTokenTTL' => [
        'default' => 'P365D',
    ],
    'wgMWOAuthSharedUserSource' => [
        'default' => 'CentralAuth',
    ],
    'wgMWOAuthSecureTokenTransfer' => [
        'default' => true,
    ],
    'wgOAuth2PublicKey' => [
        'default' => '/srv/mediawiki/config/OAuth.key.pub',
    ],
    'wgOAuth2PrivateKey' => [
        'default' => '/srv/mediawiki/config/OAuth.key',
    ],

    // PortableInfobox
    'wgPortableInfoboxResponsiblyOpenCollapsed' => [
        'default' => true,
    ],
    'wgPortableInfoboxUseFileDescriptionPage' => [
        'default' => false,
    ],
    'wgPortableInfoboxUseHeadings' => [
        'default' => true,
    ],
    'wgPortableInfoboxCacheRenderers' => [
        'default' => true,
    ],
    'wgPortableInfoboxCustomImageWidth' => [
        'default' => 300,
    ],

    // JsonConfig
    'wgJsonConfigEnableLuaSupport' => [
        'default' => true,
    ],
    'wgJsonConfigInterwikiPrefix' => [
        'default' => 'commons',
    ],
    'wgJsonConfigModels' => [
        'default' => [
            'Map.JsonConfig' => JsonConfig\JCMapDataContent::class,
            'Tabular.JsonConfig' => JsonConfig\JCTabularContent::class,
        ],
    ],

    // Interwiki & InterwikiDispatcher
    'wgIWDPrefixes' => [
        'default' => [
            'fandom' => [
                /* Fandom */
                'interwiki' => 'fandom',
                'url' => 'https://$2.fandom.com/wiki/$1',
                'urlInt' => 'https://$2.fandom.com/$3/wiki/$1',
                'baseTransOnly' => true,
            ],
            'miraheze' => [
                /* Miraheze */
                'interwiki' => 'mh',
                'url' => 'https://$2.miraheze.org/wiki/$1',
                'baseTransOnly' => true,
            ],
            'kyoikuportal' => [
                /* KP */
                'interwiki' => 'kp',
                'url' => 'https://$2.kyoikuportal.com/wiki/$1',
                'baseTransOnly' => true,
            ],
            'wikioasis' => [
                /* WikiOasis */
                'interwiki' => 'wo',
                'url' => 'https://$2.wikioasis.org/wiki/$1',
                'dbname' => '$2wiki',
                'baseTransOnly' => true,
            ],
            'skywiki' => [
                /* Skywiki */
                'interwiki' => 'sw',
                'url' => 'https://$2.skywiki.org/wiki/$1',
                'baseTransOnly' => true,
            ],
        ],
    ],
    'wgInterwikiCentralDB' => [
        'default' => 'metawiki',
	'default' => 'metawikibeta',
    ],
    'wgInterwikiMagic' => [
	'default' => false,
    ],

    // ConfirmEdit & hCaptcha
    'wgCaptchaTriggers' => [
        'default' => [
            'edit' => false,
            'create' => false,
            'sendemail' => false,
            'addurl' => true,
            'createaccount' => true,
            'badlogin' => true,
            'badloginperuser' => true,
        ],
        '+ext-WikiForum' => [
            'wikiforum' => true,
        ],
        '+ext-ContactPage' => [
            'contactpage' => true,
        ],
    ],
    'wgHCaptchaSiteKey' => [
        'default' => 'adce63ae-507e-4d80-8f9a-9a443e3658d6',
    ],

    // TimedMediaHandler
    'wgFFmpegLocation' => [
        'default' => '/usr/bin/ffmpeg',
    ],
    'wgTimelinePloticusCommand' => [
        'default' => '/usr/bin/ploticus',
    ],

    // Echo
    'wgEchoCrossWikiNotifications' => [
        'default' => true,
    ],
    'wgEchoSharedTrackingDB' => [
        'default' => 'wikidb',
	'beta' => 'wikidbbeta',
    ],
    'wgEchoUseJobQueue' => [
        'default' => true,
    ],
    'wgEchoUseCrossWikiBetaFeature' => [
        'default' => false,
    ],
    'wgEchoMentionStatusNotifications' => [
        'default' => true,
    ],
    'wgEchoMaxMentionsInEditSummary' => [
        'default' => 5,
    ],
    'wgEchoPerUserBlacklist' => [
        'default' => true,
    ],
    'wgEchoWatchlistNotifications' => [
        'default' => false,
    ],
    'wgEchoWatchlistEmailOncePerPage' => [
        'default' => true,
    ],

    // UserPageEditProtection
    'wgOnlyUserEditUserPage' => [
        'ext-UserPageEditProtection' => true,
    ],

    // Citoid
    'wgCitoidFullRestbaseURL' => [
        'default' => 'https://en.wikipedia.org/api/rest_',
    ],

    // Cargo
    'wgCargoDBuser' => [
        'default' => 'cargouser2025',
    ],
    'wgCargoFileDataColumns' => [
        'default' => [],
    ],
    'wgCargoPageDataColumns' => [
        'default' => [],
    ],
    // CentralNotice
    'wgNoticeInfrastructure' => [
        'default' => true,
        'metawiki' => true,
        'metawikibeta' => true,
    ],
    'wgCentralSelectedBannerDispatcher' => [
        'default' => 'https://meta.wikioasis.org/wiki/Special:BannerLoader',
    ],
    'wgCentralBannerRecorder' => [
        'default' => 'https://meta.wikioasis.org/wiki/Special:RecordImpression',
    ],
    'wgCentralDBname' => [
        'default' => 'metawiki',
	'beta' => 'metawikibeta',
    ],
    'wgCentralHost' => [
        'default' => 'https://meta.wikioasis.org',
    ],
    'wgNoticeProjects' => [
        'default' => [
            'all',
            'optout',
        ],
    ],
    'wgNoticeUseTranslateExtension' => [
        'default' => true,
    ],
    'wgNoticeProject' => [
        'default' => 'all',
    ],

    // PageImages
    'wgPageImagesNamespaces' => [
        'default' => [
            NS_MAIN,
        ],
        'countryhumanswiki' => [
            NS_MAIN,
            3000
        ],
        'objectshowwiki' => [
            NS_MAIN,
            3000,
            3006,
            3008,
            3010,
            3012,
            3014,
            3016,
            3018,
            3020,
            3022,
			3024,
			3026
        ],
    ],

    // DismissableSiteNotice
    'wgDismissableSiteNoticeForAnons' => [
        'default' => true,
    ],

    // CookieWarning
    'wgCookieWarningMoreUrl' => [
        'default' => 'https://meta.wikioasis.org/wiki/Privacy_policy',
    ],
    'wgCookieWarningEnabled' => [
        'default' => true,
    ],

    // SpamBlacklist
    'wgLogSpamBlacklistHits' => [
        'default' => true,
    ],
    'wgBlacklistSettings' => [
        'default' => [
            'spam' => [
                'files' => [
                    "https://meta.wikioasis.org/w/index.php?title=Spam_blacklist&action=raw&sb_ver=1"
                ],
            ],
        ]
    ],

    // TitleBlacklist
    'wgTitleBlacklistLogHits' => [
        'default' => true,
    ],
    'wgTitleBlacklistSources' => [
        'default' => [
            'global' => [
                'type' => 'url',
                'src' => 'https://meta.wikioasis.org/w/index.php?title=Global_title_blacklist&action=raw',
            ],
            'local' => [
                'type' => 'localpage',
                'src' => 'MediaWiki:Titleblacklist',
            ],
        ],
    ],
    'wgTitleBlacklistUsernameSources' => [
        'default' => '*',
    ],
    'wgTitleBlacklistBlockAutoAccountCreation' => [
        'default' => false,
    ],

    'wgKartographerDfltStyle' => [
        'default' => '.',
    ],
    'wgKartographerEnableMapFrame' => [
        'default' => true,
    ],
    'wgKartographerMapServer' => [
        'default' => 'https://tile.openstreetmap.org',
    ],
    'wgKartographerSrcsetScales' => [
        'default' => [],
    ],
    'wgKartographerStaticMapframe' => [
        'default' => false,
    ],
    'wgKartographerSimpleStyleMarkers' => [
        'default' => true,
    ],
    'wgKartographerStyles' => [
        'default' => [
            'osm-intl',
            'osm',
        ],
    ],
    'wgKartographerUseMarkerStyle' => [
        'default' => false,
    ],
    'wgKartographerWikivoyageMode' => [
        'default' => false,
    ],

    // 'RemovePII
    'wgRemovePIIAllowedWikis' => [
        'default' => [
            'metawiki',
	    'metawikibeta',
        ],
    ],
    'wgRemovePIIAutoPrefix' => [
        'default' => 'WikiOasisGDPR',
    ],
    'wgRemovePIIHashPrefixOptions' => [
        'default' => [
            'Trust and Safety' => 'Deleted_User_',
            'Stewards' => 'Vanished User ',
        ],
    ],
    'wgRemovePIIHashPrefix' => [
        'default' => 'Deleted_User_',
    ],

    // ImportDump
    'wgImportDumpCentralWiki' => [
        'default' => 'metawiki',
	'beta' => 'metawikibeta',
    ],
    'wgImportDumpEnableAutomatedJob' => [
        'default' => true,
    ],
    'wgImportDumpInterwikiMap' => [
        'default' => [
            'fandom.com' => 'wikia',
            'miraheze.org' => 'mh',
            // 'wikitide.org' => 'wt',
        ],
    ],
    'wgImportDumpScriptCommand' => [
        'default' => 'php {IP}/maintenance/importDump.php --wiki={wiki} --username-prefix="{username-prefix}" {file-path}',
        'metawikibeta' => 'screen -d -m bash -c ". /etc/swift-env.sh; swift download miraheze-metawikibeta-local-public {file-path} -o /home/$USER/{file-name}; mwscript importDump.php {wiki} -y --no-updates --username-prefix={username-prefix} /home/$USER/{file-name}; mwscript rebuildall.php {wiki} -y; mwscript initSiteStats.php {wiki} --active --update -y; rm /home/$USER/{file-name}"',
    ],
    'wgImportDumpUsersNotifiedOnAllRequests' => [
        'default' => [
            'Waki285',
        ],
    ],
    'wgImportDumpUsersNotifiedOnFailedImports' => [
        'default' => [
            'Waki285',
        ],
    ],

    // RequestCustomDomain
    'wgRequestCustomDomainUsersNotifiedOnAllRequests' => [
        'default' => [
            'Zippy',
        ],
    ],

    // WikiOasisMagic
    'wgWikiOasisMagicReportsBlockAlertKeywords' => [
	'default' => [
	    'underage',
	    'under age',
	    'under 13',
        'threat',
        'threats',
	    'death threats',
	    'death threat',
        'cp',
        'child',
	    'child pornography',
	    'images of children',
	    'images of minors',
	    'suicide',
        'kms',
        'kys',
	    'kill me',
	    'kill themselves',
	    'kill themselfs',
	    'kill themself',
	    'murder',
	    'terrorist',
	    'terrorism',
	    'bomb threat',
	    'bomb hoax',
	],
    ],

    // Comments
    'wgCommentsDefaultAvatar' => [
        'default' => '/extensions/SocialProfile/avatars/default_ml.gif',
    ],

    // DiscordNotifications
    'wgDiscordAvatarUrl' => [
        'default' => '',
    ],
    'wgDiscordFromName' => [
        'default' => $wi->sitename,
    ],
    'wgDiscordIgnoreMinorEdits' => [
        'default' => false,
    ],
    'wgDiscordIncludePageUrls' => [
        'default' => true,
    ],
    'wgDiscordIncludeUserUrls' => [
        'default' => true,
    ],
    'wgDiscordIncludeDiffSize' => [
        'default' => true,
    ],
    'wgDiscordNotificationMovedArticle' => [
        'default' => true,
    ],
    'wgDiscordNotificationFileUpload' => [
        'default' => true,
    ],
    'wgDiscordNotificationProtectedArticle' => [
        'default' => true,
    ],
    'wgDiscordNotificationAfterImportPage' => [
        'default' => true,
    ],
    'wgDiscordNotificationShowSuppressed' => [
        'default' => false,
    ],
    'wgDiscordNotificationCentralAuthWikiUrl' => [
        'default' => 'https://meta.wikioasis.org/',
    ],
    'wgDiscordNotificationBlockedUser' => [
        'default' => true,
    ],
    'wgDiscordNotificationNewUser' => [
        'default' => true,
    ],
    'wgDiscordNotificationIncludeAutocreatedUsers' => [
        'default' => true,
        'loginwiki' => false,
        'metawiki' => false,
    ],
    'wgDiscordAdditionalIncomingWebhookUrls' => [
        'default' => [],
    ],
    'wgDiscordDisableEmbedFooter' => [
        'default' => false,
    ],
    'wgDiscordExcludeConditions' => [
        'default' => [
            'experimental' => [
                'article_inserted' => [
                    'groups' => [
                        'sysop',
                    ],
                    'permissions' => [
                        'bot',
                        'managewiki-core',
                        'managewiki-extensions',
                        'managewiki-namespaces',
                        'managewiki-permissions',
                        'managewiki-settings',
                    ],
                ],
                'article_saved' => [
                    'groups' => [
                        'sysop',
                    ],
                    'permissions' => [
                        'bot',
                        'managewiki-core',
                        'managewiki-extensions',
                        'managewiki-namespaces',
                        'managewiki-permissions',
                        'managewiki-settings',
                    ],
                ],
            ],
            'users' => [],
        ],
        '+metawiki' => [
            'article_inserted' => [
                'groups' => [
                    'bot',
                    'flood',
                ],
            ],
            'article_saved' => [
                'groups' => [
                    'bot',
                    'flood',
                ],
            ],
        ],
    ],
    'wgDiscordEnableExperimentalCVTFeatures' => [
        'default' => true,
    ],
    'wgDiscordExperimentalCVTMatchFilter' => [
        'default' => [ '(n[1i!*]gg[3*e]r|r[e3*]t[4@*a]rd|f[@*4]gg[0*o]t|ch[1!i*]nk)' ],
    ],
    'wgDiscordExperimentalFeedLanguageCode' => [
        'default' => 'en',
    ],

    // SecurePoll
    'wgSecurePollUseLogging' => [
        'default' => true,
    ],
    'wgSecurePollSingleTransferableVoteEnabled' => [
        'default' => true,
    ],
    'wgSecurePollUseNamespace' => [
        'default' => true,
    ],

    // Scribunto
    'wgCodeEditorEnableCore' => [
        'default' => true,
    ],
    'wgScribuntoDefaultEngine' => [
        'default' => 'luasandbox',
    ],
    'wgScribuntoUseCodeEditor' => [
        'default' => true,
    ],
    'wgScribuntoSlowFunctionThreshold' => [
        'default' => 0.99,
    ],

    // Wikibase
    'wmgAllowEntityImport' => [
        'default' => false,
    ],
    'wmgCanonicalUriProperty' => [
        'default' => false,
    ],
    'wmgEnableEntitySearchUI' => [
        'default' => false,
    ],
    'wmgFederatedPropertiesEnabled' => [
        'default' => false,
    ],
    'wmgFormatterUrlProperty' => [
        'default' => false,
    ],
    'wmgWikibaseRepoDatabase' => [
        'default' => $wi->dbname
    ],
    'wmgWikibaseRepoUrl' => [
        'default' => 'https://wikidata.org'
    ],
    'wmgWikibaseItemNamespaceID' => [
        'default' => 0
    ],
    'wmgWikibasePropertyNamespaceID' => [
        'default' => 120
    ],
    'wmgWikibaseRepoItemNamespaceID' => [
        'default' => 860
    ],
    'wmgWikibaseRepoPropertyNamespaceID' => [
        'default' => 862
    ],
    // Widgets
    'wgWidgetsCompileDir' => [
        'default' => '$IP/cache/$wgDBname/compiled_templates',
    ],
    // WikibaseLexeme
    'wgLexemeLanguageCodePropertyId' => [
        'default' => null,
    ],
    'wgLexemeEnableDataTransclusion' => [
        'default' => false,
    ],

    // WikibaseQualityConstraints
    'wgWBQualityConstraintsInstanceOfId' => [
        'default' => 'P31',
    ],
    'wgWBQualityConstraintsSubclassOfId' => [
        'default' => 'P279',
    ],
    'wgWBQualityConstraintsStartTimePropertyIds' => [
        'default' => [
            'P569',
            'P571',
            'P580',
            'P585',
        ],
        'gratisdatawiki' => [
            'P26',
            'P11',
            'P174',
            'P80',
        ],
    ],
    'wgWBQualityConstraintsEndTimePropertyIds' => [
        'default' => [
            'P570',
            'P576',
            'P582',
            'P585',
        ],
        'gratisdatawiki' => [
            'P132',
            'P539',
            'P175',
            'P80',
        ],
    ],
    'wgWBQualityConstraintsPropertyConstraintId' => [
        'default' => 'P2302',
    ],
    'wgWBQualityConstraintsExceptionToConstraintId' => [
        'default' => 'P2303',
    ],
    'wgWBQualityConstraintsConstraintStatusId' => [
        'default' => 'P2316',
    ],
    'wgWBQualityConstraintsMandatoryConstraintId' => [
        'default' => 'Q21502408',
    ],
    'wgWBQualityConstraintsSuggestionConstraintId' => [
        'default' => 'Q62026391',
    ],
    'wgWBQualityConstraintsDistinctValuesConstraintId' => [
        'default' => 'Q21502410',
    ],
    'wgWBQualityConstraintsMultiValueConstraintId' => [
        'default' => 'Q21510857',
    ],
    'wgWBQualityConstraintsUsedAsQualifierConstraintId' => [
        'default' => 'Q21510863',
    ],
    'wgWBQualityConstraintsSingleValueConstraintId' => [
        'default' => 'Q19474404',
    ],
    'wgWBQualityConstraintsSymmetricConstraintId' => [
        'default' => 'Q21510862',
    ],
    'wgWBQualityConstraintsTypeConstraintId' => [
        'default' => 'Q21503250',
    ],
    'wgWBQualityConstraintsValueTypeConstraintId' => [
        'default' => 'Q21510865',
    ],
    'wgWBQualityConstraintsInverseConstraintId' => [
        'default' => 'Q21510855',
    ],
    'wgWBQualityConstraintsItemRequiresClaimConstraintId' => [
        'default' => 'Q21503247',
    ],
    'wgWBQualityConstraintsValueRequiresClaimConstraintId' => [
        'default' => 'Q21510864',
    ],
    'wgWBQualityConstraintsConflictsWithConstraintId' => [
        'default' => 'Q21502838',
    ],
    'wgWBQualityConstraintsOneOfConstraintId' => [
        'default' => 'Q21510859',
    ],
    'wgWBQualityConstraintsMandatoryQualifierConstraintId' => [
        'default' => 'Q21510856',
    ],
    'wgWBQualityConstraintsAllowedQualifiersConstraintId' => [
        'default' => 'Q21510851',
    ],
    'wgWBQualityConstraintsRangeConstraintId' => [
        'default' => 'Q21510860',
    ],
    'wgWBQualityConstraintsDifferenceWithinRangeConstraintId' => [
        'default' => 'Q21510854',
    ],
    'wgWBQualityConstraintsCommonsLinkConstraintId' => [
        'default' => 'Q21510852',
    ],
    'wgWBQualityConstraintsContemporaryConstraintId' => [
        'default' => 'Q25796498',
    ],
    'wgWBQualityConstraintsFormatConstraintId' => [
        'default' => 'Q21502404',
    ],
    'wgWBQualityConstraintsUsedForValuesOnlyConstraintId' => [
        'default' => 'Q21528958',
    ],
    'wgWBQualityConstraintsUsedAsReferenceConstraintId' => [
        'default' => 'Q21528959',
    ],
    'wgWBQualityConstraintsNoBoundsConstraintId' => [
        'default' => 'Q51723761',
    ],
    'wgWBQualityConstraintsAllowedUnitsConstraintId' => [
        'default' => 'Q21514353',
    ],
    'wgWBQualityConstraintsSingleBestValueConstraintId' => [
        'default' => 'Q52060874',
    ],
    'wgWBQualityConstraintsAllowedEntityTypesConstraintId' => [
        'default' => 'Q52004125',
    ],
    'wgWBQualityConstraintsCitationNeededConstraintId' => [
        'default' => 'Q54554025',
    ],
    'wgWBQualityConstraintsPropertyScopeConstraintId' => [
        'default' => 'Q53869507',
    ],
    'wgWBQualityConstraintsLexemeLanguageConstraintId' => [
        'default' => 'Q55819106',
    ],
    'wgWBQualityConstraintsLabelInLanguageConstraintId' => [
        'default' => 'Q108139345',
    ],
    'wgWBQualityConstraintsLanguagePropertyId' => [
        'default' => 'P424',
    ],
    'wgWBQualityConstraintsClassId' => [
        'default' => 'P2308',
    ],
    'wgWBQualityConstraintsRelationId' => [
        'default' => 'P2309',
    ],
    'wgWBQualityConstraintsInstanceOfRelationId' => [
        'default' => 'Q21503252',
    ],
    'wgWBQualityConstraintsSubclassOfRelationId' => [
        'default' => 'Q21514624',
    ],
    'wgWBQualityConstraintsInstanceOrSubclassOfRelationId' => [
        'default' => 'Q30208840',
    ],
    'wgWBQualityConstraintsPropertyId' => [
        'default' => 'P2306',
    ],
    'wgWBQualityConstraintsQualifierOfPropertyConstraintId' => [
        'default' => 'P2305',
    ],
    'wgWBQualityConstraintsMinimumQuantityId' => [
        'default' => 'P2313',
    ],
    'wgWBQualityConstraintsMaximumQuantityId' => [
        'default' => 'P2312',
    ],
    'wgWBQualityConstraintsMinimumDateId' => [
        'default' => 'P2310',
    ],
    'wgWBQualityConstraintsMaximumDateId' => [
        'default' => 'P2311',
    ],
    'wgWBQualityConstraintsNamespaceId' => [
        'default' => 'P2307',
    ],
    'wgWBQualityConstraintsFormatAsARegularExpressionId' => [
        'default' => 'P1793',
    ],
    'wgWBQualityConstraintsSyntaxClarificationId' => [
        'default' => 'P2916',
    ],
    'wgWBQualityConstraintsConstraintScopeId' => [
        'default' => 'P4680',
    ],
    'wgWBQualityConstraintsConstraintEntityTypesId' => [
        'default' => 'P4680',
    ],
    'wgWBQualityConstraintsSeparatorId' => [
        'default' => 'P4155',
    ],
    'wgWBQualityConstraintsConstraintCheckedOnMainValueId' => [
        'default' => 'Q46466787',
    ],
    'wgWBQualityConstraintsConstraintCheckedOnQualifiersId' => [
        'default' => 'Q46466783',
    ],
    'wgWBQualityConstraintsConstraintCheckedOnReferencesId' => [
        'default' => 'Q46466805',
    ],
    'wgWBQualityConstraintsNoneOfConstraintId' => [
        'default' => 'Q52558054',
    ],
    'wgWBQualityConstraintsIntegerConstraintId' => [
        'default' => 'Q52848401',
    ],
    'wgWBQualityConstraintsWikibaseItemId' => [
        'default' => 'Q29934200',
    ],
    'wgWBQualityConstraintsWikibasePropertyId' => [
        'default' => 'Q29934218',
    ],
    'wgWBQualityConstraintsWikibaseLexemeId' => [
        'default' => 'Q51885771',
    ],
    'wgWBQualityConstraintsWikibaseFormId' => [
        'default' => 'Q54285143',
    ],
    'wgWBQualityConstraintsWikibaseSenseId' => [
        'default' => 'Q54285715',
    ],
    'wgWBQualityConstraintsWikibaseMediaInfoId' => [
        'default' => 'Q59712033',
    ],
    'wgWBQualityConstraintsPropertyScopeId' => [
        'default' => 'P5314',
    ],
    'wgWBQualityConstraintsAsMainValueId' => [
        'default' => 'Q54828448',
    ],
    'wgWBQualityConstraintsAsQualifiersId' => [
        'default' => 'Q54828449',
    ],
    'wgWBQualityConstraintsAsReferencesId' => [
        'default' => 'Q54828450',
    ],
    'wgWBQualityConstraintsEnableSuggestionConstraintStatus' => [
        'default' => false,
    ],

    // WikiSEO configs
    'wgTwitterCardType' => [
        'default' => 'summary_large_image',
    ],
    'wgGoogleSiteVerificationKey' => [
        'default' => false,
    ],
    'wgBingSiteVerificationKey' => [
        'default' => false,
    ],
    'wgFacebookAppId' => [
        'default' => false,
    ],
    'wgYandexSiteVerificationKey' => [
        'default' => false,
    ],
    'wgAlexaSiteVerificationKey' => [
        'default' => false,
    ],
    'wgPinterestSiteVerificationKey' => [
        'default' => false,
    ],
    'wgNaverSiteVerificationKey' => [
        'default' => false,
    ],
    'wgWikiSeoDefaultImage' => [
        'default' => null,
    ],
    'wgWikiSeoDisableLogoFallbackImage' => [
        'default' => false,
    ],
    'wgWikiSeoEnableAutoDescription' => [
        'default' => true,
    ],
    'wgWikiSeoTryCleanAutoDescription' => [
        'default' => false,
    ],
    'wgMetadataGenerators' => [
        'default' => [
            'OpenGraph',
            'Twitter',
            'SchemaOrg',
        ],
    ],
    'wgTwitterSiteHandle' => [
        'default' => '',
    ],
    'wgWikiSeoDefaultLanguage' => [
        'default' => '',
    ],

    // DataMaps
    'wgDataMapsEnableCreateMap' => [
        'default' => true,
    ],
    'wgDataMapsEnableVisualEditor' => [
        'default' => false,
    ],
    'wgDataMapsAllowExperimentalFeatures' => [
        'default' => false,
    ],

    // Maps
    'egMapsEnableCoordinateFunction' => [
        'default' => true,
    ],

    // CFCachePurge
    'wgCFCachePurgeIgnoreImgAuth' => [
        'default' => true,
    ],
    'wgCFCachePurgePurgeableImageHosts'=> [
        'default' => ['cdn.wikioasis.org'],
    ],

    // GTag
    'wgGTagAnalyticsId' => [
        'default' => 'G-NL7M72FBFT',
    ],
    'wgGTagAnonymizeIP' => [
        'default' => true,
    ],
    'wgGTagEnableTCF' => [
        'default' => false,
    ],
    'wgGTagHonorDNT' => [
        'default' => true,
    ],
    'wgGTagTrackSensitivePages' => [
        'default' => false,
    ],
    // VisualEditor
    'wgVisualEditorEnableWikitext' =>  [
	    'default' => true,
    ],

    // QuickInstantCommons
    'wgQuickInstantCommonsUserAgentInfo' => [
        'default' => "https://wikioasis.org/; tech@wikioasis.org;"
    ],


    // CreateWiki Defined Special Variables
    'cwClosed' => [
        'default' => false,
    ],
    'cwDeleted' => [
        'default' => false,
    ],
    'cwExperimental' => [
        'default' => false,
    ],
    'cwInactive' => [
        'default' => false,
    ],
    'cwPrivate' => [
        'default' => false,
    ],
];

$wgManageWikiSiteConfiguration = $wgConf;

require_once "$IP/config/ManageWikiExtensions.php";
$wi::$disabledExtensions = [
    'drafts' => '<a href="https://issue-tracker.miraheze.org/T11970">T11970</a>',
    'score' => '<a href="https://issue-tracker.miraheze.org/T5863">T5863</a>',
    'wikiforum' => '<a href="https://issue-tracker.miraheze.org/T11641">T11641</a>',
    'mobiletabsplugin' => 'Incompatible with MediaWiki 1.42+',

    'lingo' => 'Currently broken',

    'chameleon' => 'Incompatible with MediaWiki 1.45',
    'snapwikiskin' => 'Incompatible with MediaWiki 1.45',
    'eveskin' => 'Incompatible with MediaWiki 1.45',

    'hawelcome' => 'Privacy issue',
    'semanticscribunto' => 'Semantic MediaWiki currently not enabled. Contact for enable.',
];

$globals = MirahezeFunctions::getConfigGlobals();

// profiling
require_once __DIR__ . '/Sentry.php';

// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.extract
extract( $globals );

if ( $wmgSharedDomainPathPrefix ) {
    $wgArticlePath = "{$wmgSharedDomainPathPrefix}/wiki/\$1";
    $wgServer = '//' . $wi->getSharedDomain();
}

if ( !$wmgSharedDomainPathPrefix ) {
    $wi->loadExtensions();
}
require_once __DIR__ . '/ManageWikiNamespaces.php';
require_once __DIR__ . '/ManageWikiSettings.php';

//var_dump($wgConf->settings);
require_once "$IP/config/Database.php";
require_once "$IP/config/GlobalCache.php";

$wgHooks['SetupAfterCache'][] = static function () {
    global $cwPrivate, $wgLocalFileRepo, $wgAWSRepoZones;

    if ( !$cwPrivate || !is_array( $wgLocalFileRepo ) || ( $wgLocalFileRepo['backend'] ?? null ) !== "AmazonS3" ) {
        return true;
    }

    if ( !is_array( $wgAWSRepoZones ?? null ) || !isset( $wgLocalFileRepo['zones'] ) || !is_array( $wgLocalFileRepo['zones'] ) ) {
        return true;
    }

    foreach ( $wgAWSRepoZones as $zone => $zoneInfo ) {
        if ( !empty( $zoneInfo['isPublic'] ) && isset( $wgLocalFileRepo['zones'][$zone]['url'] ) ) {
            unset( $wgLocalFileRepo['zones'][$zone]['url'] );
        }
    }

    return true;
};
// some observability here
$wgStatsFormat = 'dogstatsd';
$wgStatsTarget = 'udp://monitoring-us-east-021.ovvin.wonet:9125';
$wgWMEStatsdBaseUri = 'udp://monitoring-us-east-021.ovvin.wonet:9125';


$wmgUploadHostname = 'cdn.wikioasis.org';

// R2 storage for all wikis
$wgAWSCredentials = [
    'key' => $wgR2Key,
    'secret' => $wgR2Secret,
    'token' => false,
];
$wgAWSRegion = 'auto';
$wgAWSBucketName = 'wikioasis-media';
$wgAWSRepoHashLevels = 2;
$wgAWSRepoDeletedHashLevels = 3;
$wgAWSBucketTopSubdirectory = '/' . $wgDBname;
$wgFileBackends['s3'] = [
    'name' => 'AmazonS3',
    'class' => 'AmazonS3FileBackend',
    'lockManager' => 'nullLockManager',
    'endpoint' => $wgR2Endpoint,
    'use_path_style_endpoint' => true,
    'version' => 'latest',
    'http' => [
        'verify' => true,
        'timeout' => 30,
        'connect_timeout' => 10,
    ],
    'defaultAcl' => 'public-read',
];
$wgAWSBucketDomain = "https://cdn.wikioasis.org";

// Global containers shared across all wikis (SocialProfile avatars/awards, UserProfileV2 avatars)
$wgFileBackends['s3']['containerPaths'] = [
	'avatars'    => 'wikioasis-media/avatars',
	'awards'     => 'wikioasis-media/awards',
	'upv2avatars' => 'wikioasis-media/upv2avatars',
    "{$wgDBname}-avatars" => "wikioasis-media/{$wgDBname}/avatars",
    "{$wgDBname}-upv2avatars" => "wikioasis-media/{$wgDBname}/upv2avatars",
];
$wgAWSRepoZones['upv2avatars'] = [
    'container' => 'upv2avatars',
    'path' => 'wikioasis-media/upv2avatars',
    'isPublic' => true,
];

$wgAWSRepoZones["{$wgDBname}-upv2avatars"] = [
    'container' => $wgDBname,
    'path' => "wikioasis-media/{$wgDBname}/upv2avatars",
    'isPublic' => true,
];

$wgAWSRepoZones['avatars'] = [
    'container' => 'avatars',
    'path' => 'wikioasis-media/avatars',
    'isPublic' => true,
];

$wgAWSRepoZones["{$wgDBname}-avatars"] = [
    'container' => $wgDBname,
    'path' => "wikioasis-media/{$wgDBname}/avatars",
    'isPublic' => true,
];

$wgUserProfileV2UseGlobalAvatars = true;
$wgUserProfileGlobalUploadBaseUrl = "https://cdn.wikioasis.org/upv2avatars/";

$wgUploadDirectory = false;
$wgTmpDirectory = '/srv/mediawiki/cache';

if ( $cwPrivate ) {
   $wmgUploadHostname = false;
   $wgUploadPath = '/w/img_auth.php';
} else {
   $wgGroupPermissions['*']['read'] = true;
}

if ( $wi->missing ) {
    if ( MW_ENTRY_POINT === 'cli' ) {
        die( 'Unknown wiki.' );
    } else {
        $host = '';
        if ( PHP_SAPI !== 'cli' ) {
            $host = $_SERVER['HTTP_HOST'] ?? $_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '';
        }
        $host = strtolower( trim( preg_replace( '/:\d+$/', '', $host ) ) );

        if ( $host !== '' && preg_match( '/(^|\.)skywiki\.org$/', $host ) ) {
            require_once '/srv/mediawiki/config/MissingSkyWiki.php';
        } else {
            require_once '/srv/mediawiki/config/MissingWiki.php';
        }
    }
}


if ( $cwDeleted ) {
    if ( MW_ENTRY_POINT === 'cli' ) {
        echo "Deleted Wiki: {$wgDBname}\n";
        #wfHandleDeletedWiki();
    } else {
        define( 'MW_FINAL_SETUP_CALLBACK', 'wfHandleDeletedWiki' );
    }
}

function wfHandleDeletedWiki() {
    require_once '/srv/mediawiki/config/DeletedWiki.php';
}

require_once "$IP/config/GlobalSettings.php";
require_once "$IP/config/LocalWiki.php";

$wgCargoDBname = $wgDBname . 'cargo';

// Define last - Extension message files for loading extensions
$_mwVersion = MirahezeFunctions::getMediaWikiVersion();
if (file_exists(__DIR__ . "/ExtensionMessageFiles-{$_mwVersion}.php") && !defined('MW_NO_EXTENSION_MESSAGES')) {
    require_once __DIR__ . "/ExtensionMessageFiles-{$_mwVersion}.php";
    // These are not loaded by mergeMessageFileList.php due to not being on ExtensionRegistry
    $wgMessagesDirs['SocialProfile'] = $IP . '/extensions/SocialProfile/i18n';
    $wgExtensionMessagesFiles['SocialProfileAlias'] = $IP . '/extensions/SocialProfile/SocialProfile.alias.php';
    $wgMessagesDirs['SocialProfileUserProfile'] = $IP . '/extensions/SocialProfile/UserProfile/i18n';
    $wgExtensionMessagesFiles['SocialProfileNamespaces'] = $IP . '/extensions/SocialProfile/SocialProfile.namespaces.php';
    $wgExtensionMessagesFiles['AvatarMagic'] = $IP . '/extensions/SocialProfile/UserProfile/includes/avatar/Avatar.i18n.magic.php';
}
// Use a per-version subdirectory so multiple MW versions can coexist.
$wgLocalisationCacheConf['storeClass'] = LCStoreStaticArray::class;
$wgLocalisationCacheConf['storeDirectory'] = "/srv/mediawiki/cache/" . MirahezeFunctions::getMediaWikiVersion();
$wgLocalisationCacheConf['manualRecache'] = true;

if ( !file_exists( $wgLocalisationCacheConf['storeDirectory'] . '/en.l10n.php' ) ) {
    $wgLocalisationCacheConf['manualRecache'] = false;
}

unset( $wi );
