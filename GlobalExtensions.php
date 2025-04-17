<?php
wfLoadSkins( [
	'CologneBlue',
	'Modern',
	'MonoBook',
	'Timeless',
	'Vector',
] );

wfLoadExtensions( [
    'AbuseFilter',
    'AntiSpoof',
    'BetaFeatures',
    'CentralAuth',
    'CentralNotice',
    'CheckUser',
    'CreateWiki',
    'CookieWarning',
    'ConfirmEdit',
    'ConfirmEdit/hCaptcha',
    'DataDump',
    'DiscordNotifications',
    'DismissableSiteNotice',
    'Echo',
    //'EventBus',
    'EventLogging',
    //'EventStreamConfig',
    'GlobalBlocking',
    'GlobalCssJs',
    'GlobalPreferences',
    //'GlobalNewFiles',
    'ImportDump',
    'Interwiki',
    'InterwikiDispatcher',
    //'IPInfo',
    'LoginNotify',
    'ManageWiki',
    //'MatomoAnalytics',
    //'MobileDetect',
    //'MultiPurge',
    'NativeSvgHandler',
    'Nuke',
    'OATHAuth',
    'OAuth',
    'ParserFunctions',
    //'ParserMigration',
    'QuickInstantCommons',
    'RemovePII',
    // 'ReportIncident',
    //'RottenLinks',
    'Scribunto',
    // 'SecureLinkFixer',
    'SpamBlacklist',
    // 'StopForumSpam',
    'TitleBlacklist',
    'TorBlock',
    //'WebAuthn',
    'WikiDiscover',
    'WikiEditor',
	'WikiOasisMagic',
    'cldr',
] );

$wgEventLoggingBaseUri = '/beacon/event';
$wgEventLoggingServiceUri = '/beacon/intake-analytics';
$wgEventLoggingStreamNames = false;
