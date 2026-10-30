<?php

$wgStatsFormat = 'dogstatsd';
$wgStatsTarget = 'udp://monitoring-us-east-021.ovvin.wonet:9125';
$wgWMEStatsdBaseUri = 'udp://monitoring-us-east-021.ovvin.wonet:9125';

$wgHooks['PageSaveComplete'][] = static function () {
	if ( !in_array( MW_ENTRY_POINT, [ 'index', 'api', 'rest' ], true ) ) {
		return;
	}
	\MediaWiki\MediaWikiServices::getInstance()->getStatsFactory()
		->getCounter( 'edit_outcome_total' )
		->setLabel( 'outcome', 'success' )
		->setLabel( 'entrypoint', MW_ENTRY_POINT )
		->increment();
};

$wgHooks['LogException'][] = static function ( $e, $suppressed ) {
	if ( $suppressed || !in_array( MW_ENTRY_POINT, [ 'index', 'api', 'rest' ], true ) ) {
		return;
	}
	$request = \MediaWiki\Context\RequestContext::getMain()->getRequest();
	if ( !$request->wasPosted() ) {
		return;
	}
	$isEdit = match ( MW_ENTRY_POINT ) {
		'index' => in_array( $request->getRawVal( 'action' ), [ 'submit', 'edit' ], true ),
		'api' => in_array( $request->getRawVal( 'action' ), [ 'edit', 'visualeditoredit', 'discussiontoolsedit' ], true ),
		'rest' => str_contains( $request->getRequestURL(), '/page/' ),
	};
	if ( !$isEdit ) {
		return;
	}
	\MediaWiki\MediaWikiServices::getInstance()->getStatsFactory()
		->getCounter( 'edit_outcome_total' )
		->setLabel( 'outcome', 'error' )
		->setLabel( 'entrypoint', MW_ENTRY_POINT )
		->increment();
};
