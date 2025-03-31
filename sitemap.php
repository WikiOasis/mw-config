<?php

define( 'MW_NO_SESSION', 1 );

require_once '/var/www/mediawiki/config/MirahezeFunctions.php';
require '/var/www/mediawiki/includes/WebStart.php';

use MediaWiki\Context\RequestContext;
use MediaWiki\MediaWikiServices;

function streamSitemapIndex() {
	global $wgDBname, $wmgUploadHostname;
	wfResetOutputBuffers();

	$url = "https://{$wmgUploadHostname}/sitemaps/{$wgDBname}/sitemap-index-{$wgDBname}.xml";

	$req = RequestContext::getMain()->getRequest();
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
