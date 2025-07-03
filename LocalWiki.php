<?php

use MediaWiki\Actions\ActionEntryPoint;
use MediaWiki\Linker\Linker;
use MediaWiki\Output\OutputPage;
use MediaWiki\Request\WebRequest;
use MediaWiki\SpecialPage\DisabledSpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

// Per-wiki settings that are incompatible with LocalSettings.php
switch ( $wi->dbname ) {
	case 'metawiki':
		wfLoadExtension('SecurePoll');
		wfLoadExtension('RequestSSL');
		break;
	case 'testwiki':
		wfLoadExtension('EnhancedUpload');
		wfLoadExtension('OOJSPlus');
		wfLoadExtension('Citoid');
		$wgCitoidServiceUrl = "https://en.wikipedia.org/api/rest_v1/data/citation";

		break;
}
