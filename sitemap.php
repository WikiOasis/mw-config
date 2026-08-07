<?php

define( 'MW_NO_SESSION', 1 );

require_once '/var/www/mediawiki/config/MirahezeFunctions.php';
require '/var/www/mediawiki/includes/WebStart.php';

use MediaWiki\Context\RequestContext;
use MediaWiki\MediaWikiServices;

function streamSitemapIndex() {
	global $wgDBname, $wmgUploadHostname;
	wfResetOutputBuffers();

	$req = RequestContext::getMain()->getRequest();

	// Either the index, or one of the individual sitemap-*.xml files that it lists.
	$sitemap = $req->getVal( 'sitemap', '' );
	if ( $sitemap !== '' ) {
		if ( !preg_match( '/^sitemap-' . preg_quote( $wgDBname, '/' ) . '-[\w\-]+\.xml(\.gz)?$/', $sitemap ) ) {
			header( 'HTTP/1.1 400 Bad Request' );
			return;
		}
	} else {
		$sitemap = "sitemap-index-{$wgDBname}.xml";
	}

	$url = "https://{$wmgUploadHostname}/sitemaps/{$wgDBname}/{$sitemap}";

	if ( $req->getHeader( 'X-Sitemap-Loop' ) !== false ) {
		header( 'HTTP/1.1 500 Internal Server Error' );
		return;
	}

	$client = MediaWikiServices::getInstance()
		->getHttpRequestFactory()
		->create( $url );
	$client->setHeader( 'X-Sitemap-Loop', '1' );

	$status = $client->execute();
	if ( !$status->isOK() ) {
		header( 'HTTP/1.1 404 Not Found' );
		return;
	}

	$content = $client->getContent();
	header( 'Content-Length: ' . strlen( $content ) );
	header( 'Content-Type: ' . $client->getResponseHeader( 'Content-Type' ) );
	echo $content;
}

streamSitemapIndex();
