<?php
use MediaWiki\Actions\ActionEntryPoint;
use MediaWiki\Context\RequestContext;
use MediaWiki\EntryPointEnvironment;
use MediaWiki\MediaWikiServices;

define( 'MW_ENTRY_POINT', 'index' );

require_once dirname( __DIR__, 2 ) . '/WikiOasisFunctions.php';
WikiOasisFunctions::getMediaWiki( '' );
global $IP;

require_once "$IP/includes/PHPVersionCheck.php";
if ( function_exists( 'wfEntryPointCheck' ) ) {
	// 1.46
	wfEntryPointCheck( 'html', dirname( $_SERVER['SCRIPT_NAME'] ) );
} else {
	// 1.47
	( new \MediaWiki\PHPVersionCheck( 'html', dirname( $_SERVER['SCRIPT_NAME'] ) ) )->run();
}

require "$IP/includes/WebStart.php";

( new ActionEntryPoint(
	RequestContext::getMain(),
	new EntryPointEnvironment(),
	MediaWikiServices::getInstance()
) )->run();
