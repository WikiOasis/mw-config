<?php
/**
 * GlobalSettings.php
 * Production global settings for WikiOasis
 * This file is included by LocalSettings.php file
 *
 * @var string $IP
 * @var mixed $wgConf
 * @var mixed $wi
 */

// Protect against web entry
if (!defined('MEDIAWIKI')) {
	die('Not an entry point.');
}

$wgHooks['CreateWikiPhpBuilder'][] = 'MirahezeFunctions::onCreateWikiPhpBuilder';
$wgHooks['CreateWikiPhpGenerateDatabaseList'][] = 'MirahezeFunctions::onGenerateDatabaseLists';
$wgHooks['CreateWikiDataFactoryBuilder'][] = 'MirahezeFunctions::onCreateWikiDataFactoryBuilder';

if ( $wi->isExtensionActive( 'chameleon' ) ) {
	wfLoadExtension( 'Bootstrap' );
}

if ($wi->isExtensionActive('CirrusSearch')) {
	wfLoadExtension('Elastica');
	$wgSearchType = 'CirrusSearch';
	$wgCirrusSearchServers = ['localhost'];

	if ($wi->isExtensionActive('RelatedArticles')) {
		$wgRelatedArticlesUseCirrusSearch = true;
	}
}

if ($wi->isExtensionActive('SocialProfile')) {
	require_once "$IP/extensions/SocialProfile/SocialProfile.php";

	#$wgSocialProfileFileBackend = 'miraheze-swift';
}

// JsonConfig
if ( $wi->isExtensionActive( 'JsonConfig' ) ) {
	$wgJsonConfigs = [
		'Map.JsonConfig' => [
			'namespace' => 486,
			'nsName' => 'Data',
			// page name must end in ".map", and contain at least one symbol
			'pattern' => '/.\.map$/',
			'license' => 'CC-BY-SA 4.0',
			'isLocal' => false,
		],
		'Tabular.JsonConfig' => [
			'namespace' => 486,
			'nsName' => 'Data',
			// page name must end in ".tab", and contain at least one symbol
			'pattern' => '/.\.tab$/',
			'license' => 'CC-BY-SA 4.0',
			'isLocal' => false,
		],
	];

	$wgJsonConfigs['Map.JsonConfig']['remote'] = [
		'url' => 'https://commons.miraheze.org/w/api.php'
	];

	$wgJsonConfigs['Tabular.JsonConfig']['remote'] = [
		'url' => 'https://commons.miraheze.org/w/api.php'
	];
}

if ( $wi->isAnyOfExtensionsActive( 'WikibaseClient', 'WikibaseRepository' ) ) {
	// Includes Wikibase Configuration. There is a global and per-wiki system here.
	require_once "$IP/config/Wikibase.php";
}

if ( $wi->isAnyOfExtensionsActive( 'StandardDialogs', 'EnhancedUpload' ) ) {
	wfLoadExtension( 'OOJSPlus' );
}

$articlePath = str_replace( '$1', '', $wgArticlePath );

$wgDiscordNotificationWikiUrl = $wi->server . $articlePath;
$wgDiscordNotificationWikiUrlEnding = '';
$wgDiscordNotificationWikiUrlEndingDeleteArticle = '?action=delete';
$wgDiscordNotificationWikiUrlEndingDiff = '?diff=prev&oldid=';
$wgDiscordNotificationWikiUrlEndingEditArticle = '?action=edit';
$wgDiscordNotificationWikiUrlEndingHistory = '?action=history';
$wgDiscordNotificationWikiUrlEndingUserRights = 'Special:UserRights?user=';

// Public Wikis
if ( !$cwPrivate ) {
	$wgDiscordIncomingWebhookUrl = $wmgGlobalDiscordWebhookUrl;
}

if ($cwClosed) {
	$wgRevokePermissions = [
		'*' => [
			'block' => true,
			'createaccount' => true,
			'delete' => true,
			'edit' => true,
			'protect' => true,
			'import' => true,
			'upload' => true,
			'undelete' => true,
		],
	];

	if ($wi->isExtensionActive('Comments')) {
		$wgRevokePermissions['*']['comment'] = true;
	}
}

$wgDataDumpDirectory = '/var/www/dumps/';

$wgDataDump = [
	'xml' => [
		'file_ending' => '.xml.gz',
		'useBackendTempStore' => true,
		'generate' => [
			'type' => 'mwscript',
			'script' => "$IP/maintenance/dumpBackup.php",
			'options' => [
				'--full',
				'--logs',
				'--uploads',
				'--output',
				'gzip:/tmp/${filename}',
			],
			'arguments' => [
				'--namespaces'
			],
		],
		'limit' => 1,
		'permissions' => [
			'view' => 'view-dump',
			'generate' => 'generate-dump',
			'delete' => 'delete-dump',
		],
		'htmlform' => [
			'name' => 'namespaceselect',
			'type' => 'namespaceselect',
			'exists' => true,
			'noArgsValue' => 'all',
			'hide-if' => ['!==', 'generatedumptype', 'xml'],
			'label-message' => 'datadump-namespaceselect-label'
		],
	],
	'zip' => [
		'file_ending' => '.zip',
		'generate' => [
			'type' => 'script',
			'script' => '/usr/bin/zip',
			'options' => [
				'-r',
				"{$wgDataDumpDirectory}" . '${filename}',
				($cwPrivate ? "/var/www/images/{$wgDBname}" : "$IP/images/{$wgDBname}"),  // 条件による切り替え
			],
		],
		'limit' => 1,
		'permissions' => [
			'view' => 'view-dump',
			'generate' => 'generate-dump',
			'delete' => 'delete-dump',
		],
	],

	/*'managewiki_backup' => [
		'file_ending' => '.json',
		'generate' => [
			'type' => 'mwscript',
			'script' => "$IP/extensions/WikiOasisMagic/maintenance/generateManageWikiBackup.php",
			'options' => [
				'--filename',
				'${filename}'
			],
		],
		'limit' => 1,
		'permissions' => [
			'view' => 'view-dump',
			'generate' => 'generate-dump',
			'delete' => 'delete-dump',
		],
	],*/
];

// $wgLogos
$wgLogos = [
	'1x' => $wgLogo,
];

$wgApexLogo = [
	'1x' => $wgLogos['1x'],
	'2x' => $wgLogos['1x'],
];

if ( $wgIcon ) {
	$wgLogos['icon'] = $wgIcon;
}

if ( $wgWordmark ) {
	$wgLogos['wordmark'] = [
		'src' => $wgWordmark,
		'width' => $wgWordmarkWidth,
		'height' => $wgWordmarkHeight,
	];
}

$wgRemovePIIAutoPrefix = 'WikiOasisGDPR';
$wgRightsIcon = 'https://meta.wikioasis.org/' . '/resources/assets/licenses/cc-by-sa.png';
$wgRightsText = 'Creative Commons Attribution Share Alike';
$wgRightsUrl = 'https://creativecommons.org/licenses/by-sa/4.0/';

/**
 * Override values from ManageWiki.
 * If set in LocalSettings.php, this will be overridden
 * by wiki values there, due to caching forcing SiteConfiguration
 * values to be absolute overrides. This is however how licensing should
 * be forced. LocalSettings.php values should take priority, which they do.
 */
switch ($wmgWikiLicense) {
	case 'arr':
		$wgRightsIcon = 'https://static.miraheze.org/commonswiki/6/67/License_icon-copyright-88x31.svg';
		$wgRightsText = 'All Rights Reserved';
		$wgRightsUrl = false;
		break;
	case 'cc-by':
		$wgRightsIcon = 'https://meta.wikioasis.org/' . '/resources/assets/licenses/cc-by.png';
		$wgRightsText = 'Creative Commons Attribution 4.0 International (CC BY 4.0)';
		$wgRightsUrl = 'https://creativecommons.org/licenses/by/4.0';
		break;
	case 'cc-by-nc':
		$wgRightsIcon = 'https://mirrors.creativecommons.org/presskit/buttons/88x31/png/by-nc.png';
		$wgRightsText = 'Creative Commons Attribution-NonCommercial 4.0 International (CC BY-NC 4.0)';
		$wgRightsUrl = 'https://creativecommons.org/licenses/by-nc/4.0/';
		break;
	case 'cc-by-nd':
		$wgRightsIcon = 'https://mirrors.creativecommons.org/presskit/buttons/88x31/png/by-nd.png';
		$wgRightsText = 'Creative Commons Attribution-NoDerivatives 4.0 International (CC BY-ND 4.0)';
		$wgRightsUrl = 'https://creativecommons.org/licenses/by-nd/4.0/';
		break;
	case 'cc-by-sa':
		$wgRightsIcon = 'https://meta.wikioasis.org/' . '/resources/assets/licenses/cc-by-sa.png';
		$wgRightsText = 'Creative Commons Attribution-ShareAlike 4.0 International (CC BY-SA 4.0)';
		$wgRightsUrl = 'https://creativecommons.org/licenses/by-sa/4.0/';
		break;
	case 'cc-by-sa-2-0-kr':
		$wgRightsIcon = 'https://meta.wikioasis.org/' . '/resources/assets/licenses/cc-by-sa.png';
		$wgRightsText = 'Creative Commons BY-SA 2.0 Korea';
		$wgRightsUrl = 'https://creativecommons.org/licenses/by-sa/2.0/kr';
		break;
	case 'cc-by-sa-nc':
		$wgRightsIcon = 'https://meta.wikioasis.org/' . '/resources/assets/licenses/cc-by-nc-sa.png';
		$wgRightsText = 'Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International (CC BY-NC-SA 4.0)';
		$wgRightsUrl = 'https://creativecommons.org/licenses/by-nc-sa/4.0/';
		break;
	case 'cc-by-nc-nd':
		$wgRightsIcon = 'https://mirrors.creativecommons.org/presskit/buttons/88x31/png/by-nc-nd.png';
		$wgRightsText = 'Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International (CC BY-NC-ND 4.0)';
		$wgRightsUrl = 'https://creativecommons.org/licenses/by-nc-nd/4.0/';
		break;
	case 'cc-pd':
		$wgRightsIcon = 'https://meta.wikioasis.org/' . '/resources/assets/licenses/cc-0.png';
		$wgRightsText = 'CC0 Public Domain';
		$wgRightsUrl = 'https://creativecommons.org/publicdomain/zero/1.0/';
		break;
	case 'gpl-v3':
		$wgRightsIcon = 'https://www.gnu.org/graphics/gplv3-or-later.png';
		$wgRightsText = 'GPLv3';
		$wgRightsUrl = 'https://www.gnu.org/licenses/gpl-3.0-standalone.html';
		break;
	case 'gfdl':
		$wgRightsIcon = 'https://www.gnu.org/graphics/gfdl-logo-tiny.png';
		$wgRightsText = 'GNU Free Document License 1.3';
		$wgRightsUrl = 'https://www.gnu.org/licenses/fdl-1.3.en.html';
		break;
	case 'empty':
		break;
}

/**
 * Make sure it works to override the footer icon
 * for other overrides in LocalSettings.php.
 */
if ($wgConf->get('wgRightsIcon', $wi->dbname)) {
	$wgFooterIcons['copyright']['copyright'] = [
		'url' => $wgConf->get('wgRightsUrl', $wi->dbname),
		'src' => $wgConf->get('wgRightsIcon', $wi->dbname),
		'alt' => $wgConf->get('wgRightsText', $wi->dbname),
	];
}
