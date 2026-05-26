<?php
define( 'MW_NO_SESSION', 1 );
define( 'MW_ENTRY_POINT', 'opensearch_desc' );

require_once dirname( __DIR__, 2 ) . '/MirahezeFunctions.php';
MirahezeFunctions::getMediaWiki( '' );
global $IP;

require_once "$IP/includes/WebStart.php";

$url = wfScript( 'rest' ) . '/v1/search';
$ctype = $wgRequest->getRawVal( 'ctype' );

if ( $ctype !== null ) {
	$url = wfAppendQuery( $url, [ 'ctype' => $ctype ] );
}

$wgRequest->response()->header( 'Location: ' . $url, true, 308 );
$wgRequest->response()->header( 'Cache-control: max-age=600' );
