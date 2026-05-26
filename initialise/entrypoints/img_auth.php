<?php
use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\FileRepo\AuthenticatedFileEntryPoint;
use MediaWiki\MediaWikiServices;

define( 'MW_NO_OUTPUT_COMPRESSION', 1 );
define( 'MW_ENTRY_POINT', 'img_auth' );

require_once dirname( __DIR__, 2 ) . '/MirahezeFunctions.php';
MirahezeFunctions::getMediaWiki( '' );
global $IP;

require "$IP/includes/WebStart.php";

( new AuthenticatedFileEntryPoint(
	RequestContext::getMain(),
	new EntryPointEnvironment(),
	MediaWikiServices::getInstance()
) )->run();
