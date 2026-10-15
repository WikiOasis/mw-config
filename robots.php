<?php
header( "Content-Type: text/plain" );
header( "Cache-Control: max-age=86400" );

header( "X-Content-Type-Options: nosniff" );

$currentDomain = $_SERVER['HTTP_HOST'] ?? '';
if ( !preg_match( '/^[a-z0-9.-]+(:\d+)?$/i', $currentDomain ) ) {
	http_response_code( 400 );
	echo "Invalid host";
	exit;
}

$robotsFilePath = __DIR__ . '/robots.txt';

if ( file_exists( $robotsFilePath ) ) {
	$robotsContent = file_get_contents( $robotsFilePath );
	$robotsContent .= "\nSitemap: https://" . htmlspecialchars( $currentDomain ) . "/sitemap.xml";
	echo $robotsContent;
} else {
	echo "File not found";
}
