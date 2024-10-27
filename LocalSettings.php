<?php
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
// error_reporting( -1 );
// ini_set( 'display_errors', 1 );
ini_set( 'xdebug.var_display_max_children', - 1 );
ini_set( 'xdebug.var_display_max_data', - 1 );
ini_set( 'xdebug.var_display_max_depth', - 1 );

require_once "$IP/config/PrivateSettings.php";

$wgConf->suffixes = [ 'wiki' ];

$wmgUploadHostname = 'static.wikioasis.org';

$wgDBtype = "mysql";

$wgDBprefix = "";
$wgDBssl = false;

$wgMainCacheType = CACHE_MEMCACHED;
$wgParserCacheType = CACHE_MEMCACHED;
$wgMessageCacheType = CACHE_MEMCACHED;
$wgMemCachedServers = [ '127.0.0.1:11211' ];

$wgDiff3 = "/usr/bin/diff3";

require_once "$IP/config/GlobalExtensions.php";

$wgVirtualDomainsMapping['virtual-centralauth'] = [ 'db' => 'centralauth' ];
$wgVirtualDomainsMapping['virtual-globalblocking'] = [ 'db' => 'centralauth'];
$wgVirtualDomainsMapping['virtual-oathauth'] = [ 'db' => 'centralauth' ];
$wgVirtualDomainsMapping['virtual-importdump'] = [ 'db' => 'metawiki' ];

$wgCreateWikiUsePhpCache = true;

$wgDebugLogGroups['MirahezeFunctions'] = "/var/log/mediawiki/mf.log";
require_once "$IP/config/MirahezeFunctions.php";
$wi = new MirahezeFunctions();

$wgConf->settings += [
	// ==================
	// MAINTENANCE THINGS
	// ==================
	// make sure we don't have any jobs in the queue!

	'wgShowExceptionDetails' => [
		'default' => false,
	],
	'wgReadonly' => [
		'default' => false,
	],

	// =============
	// CORE SETTINGS
	// =============

	// limits
	'wgMaxArticleSize' => [
		'default' => 1024 * 2,
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
	'wgMaxUploadSize' => [
		'default' => 1024 * 1024 * 4096,
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

	// for nginx
	'wgArticlePath' => [
		'default' => '/wiki/$1',
	],
	'wgScriptPath' => [
		'default' => '',
	],
	'wgUsePathInfo' => [
		'default' => true,
	],
	'wgResourceBasePath' => [
		'default' => '',
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

	// css/js
	'wgAllowUserCss' => [
		'default' => true,
	],
	'wgAllowUserJs' => [
		'default' => true,
	],

	// for Cloudflare
	'wgUseCdn' => [
		'default' => true,
	],
	'wgCdnServersNoPurge' => [
		'default' => [
			// IPv4 addresses
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
		'default' => [],
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
		'mirabeta' => 'metawikibeta',
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
		'default' => 'centralauth',
	],
	'wgCentralAuthEnableGlobalRenameRequest' => [
		'default' => true,
	],
	'wgCentralAuthGlobalBlockInterwikiPrefix' => [
		'default' => 'meta',
	],
	'wgCentralAuthLoginWiki' => [
		'default' => 'loginwiki',
		'mirabeta' => 'loginwikibeta',
	],
	'wgCentralAuthOldNameAntiSpoofWiki' => [
		'default' => 'metawiki',
	],
	'wgCentralAuthPreventUnattached' => [
		'default' => true,
	],
	'wgCentralAuthTokenCacheType' => [
		'default' => CACHE_MEMCACHED,
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
	],
	'wgCreateWikiUseJobQueue' => [
		'default' => false,
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
		'default' => 'cw_cache',
	],
	'wgCreateWikiDatabaseSuffix' => [
		'default' => 'wiki',
	],
	'wgCreateWikiDisableRESTAPI' => [
		'default' => true,
		'metawiki' => false,
	],
	'wgCreateWikiGlobalWiki' => [
		'default' => 'metawiki',
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
				'Approved' => 'Please ensure your wiki complies with all aspects of the Content Policy at all times and that it does not deviate from the approved scope or else your wiki may be closed. Thank you for choosing WikiOasis!',
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
			'removed' => 999,
			'deleted' => 999,
		]
	],
	'wgCreateWikiEnableManageInactiveWikis' => [
		'default' => true,
	],
	'wgCreateWikiSQLFiles' => [
		'default' => [
			"$IP/maintenance/tables-generated.sql",
			"$IP/extensions/AbuseFilter/db_patches/mysql/tables-generated.sql",
			"$IP/extensions/AntiSpoof/sql/mysql/tables-generated.sql",
			"$IP/extensions/BetaFeatures/sql/tables-generated.sql",
			"$IP/extensions/CheckUser/schema/mysql/tables-generated.sql",
			"$IP/extensions/DataDump/sql/data_dump.sql",
			"$IP/extensions/Echo/sql/mysql/tables-generated.sql",
			"$IP/extensions/GlobalBlocking/sql/mysql/tables-generated-global_block_whitelist.sql",
			"$IP/extensions/OATHAuth/sql/mysql/tables-generated.sql",
			"$IP/extensions/OAuth/schema/mysql/tables-generated.sql",
			//"$IP/extensions/RottenLinks/sql/rottenlinks.sql",
			//"$IP/extensions/UrlShortener/schemas/tables-generated.sql",
		],
	],

	// ManageWiki
	'wgManageWiki' => [
		'default' => [
			'core' => true,
			'extensions' => true,
			'namespaces' => true,
			'permissions' => true,
			'settings' => true
		],
	],
	'wgManageWikiPermissionsAdditionalAddGroups' => [
		'default' => [],
	],
	'wgManageWikiPermissionsAdditionalRights' => [
		'default' => [
			'*' => [
				'autocreateaccount' => true,
				'read' => true,
				'oathauth-enable' => true,
				'viewmyprivateinfo' => true,
				'editmyoptions' => true,
				'editmyprivateinfo' => true,
				'editmywatchlist' => true,
				'writeapi' => true,
			],
			'checkuser' => [
				'checkuser' => true,
				'checkuser-log' => true,
				'abusefilter-privatedetails' => true,
				'abusefilter-privatedetails-log' => true,
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
			'steward' => [
				'userrights' => true,
			],
			'user' => [
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
			'global-renamer' => [
				'centralauth-rename' => true,
			],
			'global-admin' => [
				'abusefilter-modify-global' => true,
				'centralauth-lock' => true,
				'globalblock' => true,
			],
			'steward' => [
				'abusefilter-modify-global' => true,
				'centralauth-lock' => true,
				'centralauth-suppress' => true,
				'centralauth-rename' => true,
				'centralauth-unmerge' => true,
				'createwiki' => true,
				'createwiki-deleterequest' => true,
				'globalblock' => true,
				'handle-import-request-interwiki' => true,
				'handle-import-requests' => true,
				'managewiki-core' => true,
				'managewiki-extensions' => true,
				'managewiki-namespaces' => true,
				'managewiki-permissions' => true,
				'managewiki-settings' => true,
				'managewiki-restricted' => true,
				'noratelimit' => true,
				'oathauth-verify-user' => true,
				'userrights' => true,
				'userrights-interwiki' => true,
				'globalgroupmembership' => true,
				'globalgrouppermissions' => true,
				'view-private-import-requests' => true,
			],
			'trustandsafety' => [
				'userrights' => true,
				'globalblock' => true,
				'globalgroupmembership' => true,
				'globalgrouppermissions' => true,
				'userrights-interwiki' => true,
				'centralauth-lock' => true,
				'centralauth-rename' => true,
				'handle-pii' => true,
				'oathauth-disable-for-user' => true,
				'oathauth-verify-user' => true,
				'view-private-import-requests' => true,
			],
			'suppress' => [
				'createwiki-suppressrequest' => true,
				'createwiki-suppressionlog' => true,
			],
			'sysadmin' => [
				'createwiki' => true,
				'createwiki-deleterequest' => true,
				'handle-import-request-interwiki' => true,
				'handle-import-requests' => true,
				'globalgroupmembership' => true,
				'globalgrouppermissions' => true,
				'managewiki-core' => true,
				'managewiki-extensions' => true,
				'managewiki-namespaces' => true,
				'managewiki-permissions' => true,
				'managewiki-settings' => true,
				'userrights' => true,
				'userrights-interwiki' => true,
				'view-private-import-requests' => true,
			],
			'sysop' => [
				'interwiki' => true,
			],
			'user' => [
				'request-import' => true,
				'request-ssl' => true,
				'requestwiki' => true,
			],
			'wiki-creator' => [
				'createwiki' => true,
				'createwiki-deleterequest' => true,
			],
		],
	],
	'wgManageWikiPermissionsAdditionalRemoveGroups' => [
		'default' => [],

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
			'staff',
			'suppress',
			'sysadmin',
			'trustandsafety',
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

	// GlobalBlocking & GlobalPreferences & GlobalUserPage & GlobalCssJs
	'wgGlobalBlockingDatabase' => [
		'default' => 'centralauth',
	],
	'wgGlobalPreferencesDB' => [
		'default' => 'centralauth',
	],
	'wgGlobalUserPageAPIUrl' => [
		'default' => 'https://meta.wikioasis.org/api.php',
	],
	'wgGlobalUserPageDBname' => [
		'default' => 'metawiki',
	],
	'wgUseGlobalSiteCssJs' => [
		'default' => true,
	],
	'+wgResourceLoaderSources' => [
		'default' => [
			'metawiki' => [
				'apiScript' => '//meta.wikioasis.org/api.php',
				'loadScript' => '//meta.wikioasis.org/load.php',
			],
		],
	],
	'wgGlobalCssJsConfig' => [
		'default' => [
			'wiki' => 'metawiki',
			'source' => 'metawiki',
		],
	],

	// OAuth
	'wgMWOAuthCentralWiki' => [
		'default' => 'metawiki',
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
		'default' => '/var/www/mediawiki/config/OAuth.key.pub',
	],
	'wgOAuth2PrivateKey' => [
		'default' => '/var/www/mediawiki/config/OAuth.key',
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
		],
	],
	'wgInterwikiCentralDB' => [
		'default' => 'metawiki',
	],

	// ConfirmEdit & Turnstile
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
	'wgTurnstileSiteKey' => [
		'default' => '0x4AAAAAAAyhNoKEWvui-Wj7',
	],

	// TimedMediaHandler
	'wgFFmpegLocation' => [
		'default' => '/usr/bin/ffmpeg',
	],
	'wgTimelinePloticusCommand' => [
		'default' => '/usr/bin/ploticus',
	],

	// 3D
	'wg3dProcessor' => [
		'default' => [
			'/usr/bin/xvfb-run',
			'-a',
			'-s',
			'-ac -screen 0 1280x1024x24',
			'/var/www/mediawiki/3d2png/3d2png.js',
		],
	],

	// Echo
	'wgEchoCrossWikiNotifications' => [
		'default' => true,
	],
	'wgEchoUseJobQueue' => [
		'default' => true,
	],
	'wgEchoUseCrossWikiBetaFeature' => [
		'default' => true,
	],
	'wgEchoMentionStatusNotifications' => [
		'default' => true,
	],
	'wgEchoMaxMentionsInEditSummary' => [
		'default' => 0,
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

	// CentralNotice
	'wgNoticeInfrastructure' => [
		'default' => false,
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

	// RemovePII
	'wgRemovePIIAllowedWikis' => [
		'default' => [
			'metawiki',
		],
	],
	'wgRemovePIIAutoPrefix' => [
		'default' => 'Deleted_User_',
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

	// UnoirtDyno
	'wgImportDumpCentralWiki' => [
		'default' => 'metawiki',
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

];
require_once "$IP/config/ManageWikiExtensions.php";

$globals = MirahezeFunctions::getConfigGlobals();

// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.extract
extract( $globals );

$wi->loadExtensions();
require_once __DIR__ . '/ManageWikiNamespaces.php';
require_once __DIR__ . '/ManageWikiSettings.php';

//var_dump($wgConf->settings);
require_once "$IP/config/Database.php";

$wgUploadPath = "//$wmgUploadHostname/$wgDBname";
$wgUploadDirectory = "/var/www/mediawiki/images/$wgDBname";

if ( $cwPrivate ) {
	$wgUploadDirectory = "/var/www/images/$wgDBname";
	$wgUploadPath = '/img_auth.php';
}
#if ( $wi->missing ) {
#	require_once '/var/www/mediawiki/ErrorPages/MissingWiki.php';
#}

if ( $cwDeleted ) {
	if ( MW_ENTRY_POINT === 'cli' ) {
		wfHandleDeletedWiki();
	} else {
		define( 'MW_FINAL_SETUP_CALLBACK', 'wfHandleDeletedWiki' );
	}
}

function wfHandleDeletedWiki() {
	require_once '/var/www/mediawiki/ErrorPages/DeletedWiki.php';
}

require_once "$IP/config/GlobalSettings.php";

// Define last - Extension message files for loading extensions
#if (file_exists(__DIR__ . '/ExtensionMessageFiles-' . $wi->version . '.php') && !defined('MW_NO_EXTENSION_MESSAGES')) {
	#require_once __DIR__ . '/ExtensionMessageFiles-' . $wi->version . '.php';
#}

unset( $wi );