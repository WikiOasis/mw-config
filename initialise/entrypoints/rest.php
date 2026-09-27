<?php
use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\MediaWikiServices;
use MediaWiki\Rest\EntryPoint;

define( 'MW_REST_API', true );
define( 'MW_ENTRY_POINT', 'rest' );

require_once dirname(__DIR__, 2) . '/WikiOasisFunctions.php';
WikiOasisFunctions::getMediaWiki( '' );
global $IP;

require "$IP/includes/WebStart.php";

( new EntryPoint(
	EntryPoint::getMainRequest(),
	RequestContext::getMain(),
	new EntryPointEnvironment(),
	MediaWikiServices::getInstance()
) )->run();
