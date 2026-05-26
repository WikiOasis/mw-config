<?php
/**
 * Serves the wiki sitemap index and individual sitemap files by proxying
 * from the WikiOasis CDN.
 */

define( 'MW_NO_OUTPUT_COMPRESSION', 1 );
define( 'MW_ENTRY_POINT', 'index' );

require_once dirname( __DIR__, 2 ) . '/MirahezeFunctions.php';
MirahezeFunctions::getMediaWiki( '' );
global $IP;

require "$IP/includes/WebStart.php";

global $wgDBname;

$dbname = strtolower( $wgDBname );
$requestedSitemap = $_GET['sitemap'] ?? '';

if ( $requestedSitemap !== '' ) {
	if ( !preg_match( '/^sitemap-' . preg_quote( $dbname, '/' ) . '-[\w\-]+\.xml$/', $requestedSitemap ) ) {
		http_response_code( 400 );
		header( 'Content-Type: text/plain; charset=UTF-8' );
		echo 'Invalid sitemap filename.';
		exit;
	}
	$cdnPath = "sitemaps/{$requestedSitemap}";
} else {
	$cdnPath = "sitemaps/sitemap-index-{$dbname}.xml";
}

$url = "https://cdn.wikioasis.org/{$dbname}/{$cdnPath}";

$ch = curl_init( $url );
curl_setopt_array( $ch, [
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_TIMEOUT        => 10,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_MAXREDIRS      => 3,
] );

$body      = curl_exec( $ch );
$httpCode  = (int)curl_getinfo( $ch, CURLINFO_HTTP_CODE );
$curlError = curl_error( $ch );
curl_close( $ch );

if ( $body === false || $curlError !== '' ) {
	http_response_code( 503 );
	header( 'Content-Type: text/plain; charset=UTF-8' );
	echo 'Sitemap temporarily unavailable.';
	exit;
}

if ( $httpCode === 404 ) {
	http_response_code( 404 );
	header( 'Content-Type: text/plain; charset=UTF-8' );
	echo 'Sitemap not found.';
	exit;
}

if ( $httpCode !== 200 ) {
	http_response_code( 502 );
	header( 'Content-Type: text/plain; charset=UTF-8' );
	echo 'Upstream error.';
	exit;
}

header( 'Content-Type: application/xml; charset=UTF-8' );
echo $body;
