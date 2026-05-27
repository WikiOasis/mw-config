<?php
// Proxy /w/resources/* to the correct versioned MediaWiki resources directory.
// The web server should route /w/resources/... here (PATH_INFO or REQUEST_URI rewrite).

define( 'MW_NO_SESSION', 1 );

require_once dirname( __DIR__, 2 ) . '/MirahezeFunctions.php';

$version = MirahezeFunctions::getMediaWikiVersion();
$resourcesBase = '/srv/mediawiki/versions/' . $version . '/resources';

// Extract the sub-path after /resources/ from REQUEST_URI.
// Handles both direct (/w/resources/src/foo.js) and PATH_INFO variants.
$requestPath = parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH );
$subPath = preg_replace( '#^.*?/resources(?:\.php)?/?#', '', $requestPath );
$subPath = ltrim( $subPath, '/' );

if ( $subPath === '' ) {
	http_response_code( 403 );
	exit;
}

$targetPath = $resourcesBase . '/' . $subPath;
$realTarget = realpath( $targetPath );
$realBase = realpath( $resourcesBase );

// Guard against path traversal.
if (
	$realTarget === false ||
	$realBase === false ||
	!str_starts_with( $realTarget, $realBase . DIRECTORY_SEPARATOR )
) {
	http_response_code( 404 );
	exit;
}

if ( !is_file( $realTarget ) ) {
	http_response_code( 404 );
	exit;
}

static $mimeMap = [
	'js'   => 'text/javascript',
	'css'  => 'text/css',
	'png'  => 'image/png',
	'gif'  => 'image/gif',
	'jpg'  => 'image/jpeg',
	'jpeg' => 'image/jpeg',
	'svg'  => 'image/svg+xml',
	'woff' => 'font/woff',
	'woff2' => 'font/woff2',
	'ttf'  => 'font/ttf',
	'eot'  => 'application/vnd.ms-fontobject',
	'map'  => 'application/json',
	'json' => 'application/json',
	'txt'  => 'text/plain',
	'html' => 'text/html; charset=utf-8',
];

$ext = strtolower( pathinfo( $realTarget, PATHINFO_EXTENSION ) );
$mime = $mimeMap[$ext] ?? ( mime_content_type( $realTarget ) ?: 'application/octet-stream' );

header( 'Content-Type: ' . $mime );
header( 'Content-Length: ' . filesize( $realTarget ) );
header( 'Cache-Control: public, max-age=2592000' );
header( 'X-Content-Type-Options: nosniff' );

readfile( $realTarget );
