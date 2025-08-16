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
error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR);
#error_reporting(E_ALL);
ini_set( 'display_errors', E_ALL);
ini_set( 'xdebug.var_display_max_children', - 1 );
ini_set( 'xdebug.var_display_max_data', - 1 );
ini_set( 'xdebug.var_display_max_depth', - 1 );

require_once "$IP/config/PrivateSettings.php";

$wgConf->suffixes = [ 'wiki' ];

$wmgUploadHostname = 'static.wikioasis.org';

$wgDBtype = "mysql";

$wgDBprefix = "";
$wgDBssl = false;

$wgMemCachedServers = [ '127.0.0.1:11211' ];

$wgDiff3 = "/usr/bin/diff3";

require_once "$IP/config/GlobalExtensions.php";

$wgVirtualDomainsMapping['virtual-centralauth'] = [ 'db' => 'centralauth' ];
$wgVirtualDomainsMapping['virtual-checkuser-global'] = [ 'db' => 'centralauth' ];
$wgVirtualDomainsMapping['virtual-createwiki'] = [ 'db' => 'wikidb' ];
$wgVirtualDomainsMapping['virtual-createwiki-central'] = [ 'db' => 'metawiki' ];
$wgVirtualDomainsMapping['virtual-globalblocking'] = [ 'db' => 'centralauth'];
$wgVirtualDomainsMapping['virtual-managewiki'] = [ 'db' => 'wikidb' ];
$wgVirtualDomainsMapping['virtual-oathauth'] = [ 'db' => 'centralauth' ];
$wgVirtualDomainsMapping['virtual-LoginNotify'] = [ 'db' => 'centralauth' ];
$wgVirtualDomainsMapping['virtual-importdump'] = [ 'db' => 'metawiki' ];
$wgVirtualDomainsMapping['virtual-requestssl'] = [ 'db' => 'metawiki' ];

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
		'default' => true,
	],
	'wgReadOnly' => [
		'default' => false,
	//	'default' => 'Server maintenance.',
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
		'default' => 1024 * 1024 * 128,
		'wikigeniuswiki' => 1024 * 1024 * 2,
		'founderswikiwiki' => 1024 * 1024 * 2,
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
					'src' => '//static.wikioasis.org/metawiki/5/57/Wikioasis_Partner_Footer.svg',
					'alt' => 'WikiOasis Partner Icon',
					'url' => '//meta.wikioasis.org/wiki/WikiOasis_Partner_Program',
				],
			],
		],
		'wikicordwiki' => [
                        'wopartner' => [
                                'partner' => [
                                        'src' => '//static.wikioasis.org/metawiki/5/57/Wikioasis_Partner_Footer.svg',
                                        'alt' => 'WikiOasis Partner Icon',
                                        'url' => '//meta.wikioasis.org/wiki/WikiOasis_Partner_Program',
                                ],
                        ],
                ],
		'aeronauticawiki' => [
                        'wopartner' => [
                                'partner' => [
                                        'src' => '//static.wikioasis.org/metawiki/5/57/Wikioasis_Partner_Footer.svg',
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

	// for Cloudflare
	'wgUseCdn' => [
		'default' => true,
	],
	'wgCdnServers' => [
		'default' => [
			"100.82.132.124",
			"152.53.112.87",
		],
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
			'/var/www/mediawiki/config/extension-list'
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
                'Perfect request' => 'Excellent! Your request provides a clear purpose and scope. Please ensure your wiki complies with the WikiOasis Content Policy at all times. Thank you for choosing WikiOasis!',
                'Good request' => 'This is a good request. While the description could be a bit more detailed, the purpose is clear enough for approval. Please ensure your wiki always adheres to our Content Policy.',
                'Okay request' => 'Conditionally approved. The purpose is somewhat vague, but other details suggest a valid use case. Please be advised that if the wiki deviates from its implied purpose or violates our Content Policy, it may be closed.',
                'Categorized as private' => 'Your wiki request has been approved. Please note that based on its purpose, it has been categorized as a private wiki. Ensure it continues to comply with all aspects of our Content Policy.',
            ],
            'Decline reasons' => [
                'Obscene/Offensive Name/Subdomain' => 'We do not permit wikis with offensive names or subdomains. Please change the name or subdomain. Thank you.',
                'Vandalism/Trolling' => 'This wiki request is a product of vandalism or trolling.',
                'Policy: Sexual Content w/o educational value' => 'We do not permit wikis that are merely pornography hosts without educational value. Thank you for your understanding.',
                'Policy: Hate Speech' => 'This wiki is either: directly hateful, or likely to become a hotspot for hate speech. We wish you the best in trying to find a home for this project.',
                'Illegal content' => 'We cannot host a project of this nature, due to it being illegal in the US or Germany.',
                'Commercial' => 'We do not permit wikis that serve solely to promote a business, sell products, or manipulate search engine rankings.',
                'Anarchy Wiki' => 'We do not permit wikis without clear rules and structure. This includes "anarchy wikis". We wish you the best in trying to find a home for this project.',
                'Fork/Duplicate' => 'Wikis that duplicate other wikis, or substantial portions of them already on WikiOasis are not allowed. This includes "forks". We wish you the best in trying to find a home for your project.',
                'Unsuitable content' => 'We apologize, but we don\'t think this content is suitable for WikiOasis. Thank you for your understanding.',
            ],
            'On hold reasons' => [
                'On hold pending response' => 'This request is on hold pending a response from you. Please see the "Request Comments" tab and reply to the questions asked by the reviewer. Thank you.',
                'On hold pending internal review' => 'This request has been placed on hold for internal review by another Steward. Thank you for your patience.',
            ],
        ],
    ];
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
			#"$IP/extensions/LoginNotify/sql/mysql/tables-generated.sql",
			"$IP/extensions/OATHAuth/sql/mysql/tables-generated.sql",
			"$IP/extensions/OAuth/schema/mysql/tables-generated.sql",
			//"$IP/extensions/RottenLinks/sql/rottenlinks.sql",
			//"$IP/extensions/UrlShortener/schemas/tables-generated.sql",
		],
	],

	// CheckUser
	'wgCheckUserLogLogins' => [
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
	'wgManageWikiUseCustomDomains' => [
		'default' => true,
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
			'staff' => [
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
			'electionadmin' => [
				'securepoll-create-poll' => true,
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
			'staff' => [
                                'abusefilter-modify-global' => true,
                                'handle-import-request-interwiki' => true,
                                'handle-import-requests' => true,
                                'createwiki' => true,
                                'createwiki-deleterequest' => true,
                                'centralauth-unmerge' => true,
                                'userrights' => true,
                                'globalblock' => true,
                                'globalgroupmembership' => true,
                                'globalgrouppermissions' => true,
                                'userrights-interwiki' => true,
				'managewiki-core' => true,
				'managewiki-extensions' => true,
				'managewiki-namespaces' => true,
				'managewiki-permissions' => true,
				'managewiki-settings' => true,
				'managewiki-restricted' => true,
				'noratelimit' => true,
				'centralauth-merge' => true,
                                'centralauth-lock' => true,
                                'centralauth-rename' => true,
                                'handle-pii' => true,
                                'oathauth-disable-for-user' => true,
                                'oathauth-verify-user' => true,
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
				'handle-ssl-requests' => true,
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
				'view-private-ssl-requests' => true,
			],
			'sysop' => [
				'interwiki' => true,
			],
			'user' => [
				'request-import' => true,
				'request-ssl' => true,
				'requestwiki' => true,
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
	'wgBlacklistSettings' => [
		'default' => [
			'spam' => [
				'files' => [
					"https://meta.wikioasis.org/index.php?title=Spam_blacklist&action=raw&sb_ver=1"
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

	// ImportDump
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

	// RequestSSL
	'wgRequestSSLScriptCommand' => [
		'default' => '',
	],
	'wgRequestSSLUsersNotifiedOnAllRequests' => [
		'default' => [
			'Waki285',
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

	// CFCachePurge
	'wgCFCachePurgeIgnoreImgAuth' => [
		'default' => true,
	],
	'wgCFCachePurgePurgeableImageHosts'=> [
		'default' => ['static.wikioasis.org'],
	],

	// GTag
	'wgGTagAnalyticsId' => [
		'default' => '',
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
require_once "$IP/config/ManageWikiExtensions.php";
$wi::$disabledExtensions = [
	'drafts' => '<a href="https://issue-tracker.miraheze.org/T11970">T11970</a>',
	'pageproperties' => '<a href="https://issue-tracker.miraheze.org/T11641">T11641</a>',
	'score' => '<a href="https://issue-tracker.miraheze.org/T5863">T5863</a>',
	'wikiforum' => '<a href="https://issue-tracker.miraheze.org/T11641">T11641</a>',

	'lingo' => 'Currently broken',

	'chameleon' => 'Incompatible with MediaWiki 1.42',
	'evelution' => 'Incompatible with MediaWiki 1.42',
	'eveskin' => 'Incompatible with MediaWiki 1.42',
	'femiwiki' => 'Incompatible with MediaWiki 1.42',
	'snapwikiskin' => 'Incompatible with MediaWiki 1.42',
	'hawelcome' => 'Privacy issue',
	'semanticscribunto' => 'Semantic MediaWiki currently not enabled. Contact for enable.'
	#'wikibaserepository' => 'Currently not configured',
	#'wikibaseclient' => 'Currently not configured',
];

$globals = MirahezeFunctions::getConfigGlobals();

// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.extract
extract( $globals );
#if ($wi->dbname != "wikicordwiki") {
	$wi->loadExtensions();
#}
require_once __DIR__ . '/ManageWikiNamespaces.php';
require_once __DIR__ . '/ManageWikiSettings.php';

//var_dump($wgConf->settings);
require_once "$IP/config/Database.php";
require_once "$IP/config/GlobalCache.php";

$wgUploadPath = "https://$wmgUploadHostname/$wgDBname";
$wgUploadDirectory = "/var/www/mediawiki/images/$wgDBname";



if ( $cwPrivate ) {
	$wgUploadDirectory = "/var/www/images/$wgDBname";
	$wgUploadPath = '/img_auth.php';
}
if ( $wi->missing ) {
	if ( MW_ENTRY_POINT === 'cli') {
		die("Unknown wiki.");
	} else {
		require_once '/var/www/mediawiki/config/MissingWiki.php';
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
	require_once '/var/www/mediawiki/config/DeletedWiki.php';
}

require_once "$IP/config/GlobalSettings.php";
require_once "$IP/config/LocalWiki.php";

$wgCargoDBname = $wgDBname . 'cargo';

// Define last - Extension message files for loading extensions
if (file_exists(__DIR__ . '/ExtensionMessageFiles-' . $wi->version . '.php') && !defined('MW_NO_EXTENSION_MESSAGES')) {
	require_once __DIR__ . '/ExtensionMessageFiles-' . $wi->version . '.php';
	// These are not loaded by mergeMessageFileList.php due to not being on ExtensionRegistry
	$wgMessagesDirs['SocialProfile'] = $IP . '/extensions/SocialProfile/i18n';
	$wgExtensionMessagesFiles['SocialProfileAlias'] = $IP . '/extensions/SocialProfile/SocialProfile.alias.php';
	$wgMessagesDirs['SocialProfileUserProfile'] = $IP . '/extensions/SocialProfile/UserProfile/i18n';
	$wgExtensionMessagesFiles['SocialProfileNamespaces'] = $IP . '/extensions/SocialProfile/SocialProfile.namespaces.php';
	$wgExtensionMessagesFiles['AvatarMagic'] = $IP . '/extensions/SocialProfile/UserProfile/includes/avatar/Avatar.i18n.magic.php';
}

$wgLocalisationCacheConf['storeClass'] = LCStoreStaticArray::class;
$wgLocalisationCacheConf['storeDirectory'] = '/var/www/mediawiki/cache';
$wgLocalisationCacheConf['manualRecache'] = true;

if ( !file_exists( '/var/www/mediawiki/cache/en.l10n.php' ) ) {
	$wgLocalisationCacheConf['manualRecache'] = false;
}

unset( $wi );
