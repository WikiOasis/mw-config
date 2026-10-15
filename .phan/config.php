<?php

$cfg = require_once __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$mwPath = getenv( 'MW_INSTALL_PATH' ) ?: __DIR__ . '/../../..';
$coreDirs = [ 'includes', 'languages', 'maintenance', 'vendor' ];

$cfg['directory_list'] = [
	'.',
	...array_map( static fn ( $dir ) => "$mwPath/$dir", $coreDirs ),
];

$cfg['exclude_analysis_directory_list'] = [
	'vendor',
	'node_modules',
	...array_map( static fn ( $dir ) => "$mwPath/$dir", $coreDirs ),
];

return $cfg;
