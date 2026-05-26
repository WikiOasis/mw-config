<?php
use MediaWiki\Api\ApiEntryPoint;
use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\MediaWikiServices;

define( 'MW_API', true );
define( 'MW_ENTRY_POINT', 'api' );

require_once dirname( __DIR__, 2 ) . '/MirahezeFunctions.php';
MirahezeFunctions::getMediaWiki( '' );
global $IP;

require "$IP/includes/WebStart.php";

( new ApiEntryPoint(
	RequestContext::getMain(),
	new EntryPointEnvironment(),
	MediaWikiServices::getInstance()
) )->run();
