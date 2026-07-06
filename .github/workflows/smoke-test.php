<?php
/**
 * Boots this repo's LocalSettings.php against a real MediaWiki core checkout
 * to catch fatal errors (removed core APIs, typos, bad requires) without
 * needing a database or memcached.
 *
 * MirahezeFunctions resolves wiki existence from the CreateWiki on-disk
 * cache, not a live DB query, so a wiki that has no cache entry cleanly
 * hits LocalSettings.php's own `die( 'Unknown wiki.' )` guard instead of
 * fataling. That die() is exit code 0, so reaching it means every line of
 * config before it executed without error; any real PHP fatal error
 * upstream of that point exits non-zero and fails the CI step.
 */

$ip = $argv[1] ?? null;
if ( $ip === null ) {
	fwrite( STDERR, "Usage: php smoke-test.php <path-to-mediawiki-core>\n" );
	exit( 1 );
}

define( 'MEDIAWIKI', true );
define( 'MW_ENTRY_POINT', 'cli' );
define( 'MW_DB', 'examplewiki' );
define( 'MW_INSTALL_PATH', $ip );

$IP = $ip;
$wgExtensionDirectory = "$IP/extensions";
$wgStyleDirectory = "$IP/skins";

// PrivateSettings.php holds real secrets and is gitignored; LocalSettings.php
// just needs it to exist and define these globals.
$privateSettings = "$IP/config/PrivateSettings.php";
if ( !file_exists( $privateSettings ) ) {
	file_put_contents( $privateSettings, <<<'PHP'
<?php
$wgDBserver = 'localhost';
$wgDBuser = 'root';
$wgDBpassword = '';
$wgSecretKey = 'smoke-test';
$wgUpgradeKey = 'smoke-test';
PHP );
}

require "$IP/vendor/autoload.php";
require "$IP/includes/AutoLoader.php";
require "$IP/includes/Defines.php";
require "$IP/includes/GlobalFunctions.php";

$wgConf = new MediaWiki\Config\SiteConfiguration();
$wgContentNamespaces = [ NS_MAIN ];

require "$IP/config/LocalSettings.php";
