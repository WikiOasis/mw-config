<?php
use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\MediaWikiServices;
use MediaWiki\ResourceLoader\ResourceLoaderEntryPoint;

define( 'MW_NO_SESSION', 1 );
define( 'MW_ENTRY_POINT', 'load' );

require_once dirname( __DIR__, 2 ) . '/MirahezeFunctions.php';
MirahezeFunctions::getMediaWiki( '' );
global $IP;

require "$IP/includes/WebStart.php";

( new ResourceLoaderEntryPoint(
	RequestContext::getMain(),
	new EntryPointEnvironment(),
	MediaWikiServices::getInstance()
) )->run();
