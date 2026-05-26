<?php
/**
 * MultiVersion.php
 * Fast-path version overrides and CreateWiki cache bridge.
 *
 * wikiVersions.php is the source of truth for per-wiki version overrides set
 * via Special:ManageWiki/core.  This file:
 *
 *   1. Exposes static helpers to read/write wikiVersions.php atomically.
 *   2. Implements onCreateWikiPhpBuilder to propagate version overrides into
 *      the databases.php cache so MirahezeFunctions::getMediaWikiVersion()
 *      can find them without re-reading the file on every request.
 *
 * The ManageWiki UI (onManageWikiCoreAddFormFields / onManageWikiCoreFormSubmission)
 * is handled by MirahezeFunctions and stores the chosen version in the
 * wiki_extra JSON column of cw_wikis via ManageWiki's setExtraFieldData().
 * wikiVersions.php serves as a lightweight override that takes effect
 * immediately (before the next full cache rebuild).
 */

class WikiFarmMultiVersion {

	private const VERSIONS_FILE = '/srv/mediawiki/config/wikiVersions.php';

	/** Return the version currently stored for a wiki, or the farm default. */
	public static function getWikiVersion( string $dbname ): string {
		$versions = @include self::VERSIONS_FILE;
		if ( is_array( $versions ) && isset( $versions[$dbname] ) ) {
			return $versions[$dbname];
		}
		return MirahezeFunctions::MEDIAWIKI_VERSIONS[
			MirahezeFunctions::getDefaultMediaWikiVersion()
		];
	}

	/**
	 * Atomically write a new version mapping for $dbname into wikiVersions.php.
	 * Uses a tmp-file + rename to avoid partial reads during concurrent requests.
	 * Pass null to remove a wiki-specific override (revert to farm default).
	 */
	public static function setWikiVersion( string $dbname, ?string $version ): void {
		$versions = @include self::VERSIONS_FILE;
		if ( !is_array( $versions ) ) {
			$versions = [];
		}

		$default = MirahezeFunctions::MEDIAWIKI_VERSIONS[
			MirahezeFunctions::getDefaultMediaWikiVersion()
		];

		if ( $version === null || $version === $default ) {
			unset( $versions[$dbname] );
		} else {
			$versions[$dbname] = $version;
		}

		$lines = "<?php\nreturn [\n";
		foreach ( $versions as $db => $ver ) {
			$lines .= "\t" . var_export( $db, true ) . ' => ' . var_export( $ver, true ) . ",\n";
		}
		$lines .= "];\n";

		$tmp = self::VERSIONS_FILE . '.tmp.' . getmypid();
		file_put_contents( $tmp, $lines, LOCK_EX );
		rename( $tmp, self::VERSIONS_FILE );

		// Invalidate the wiki's CreateWiki cache so databases.php picks up the
		// updated 'v' key on the next rebuild.
		$cacheFile = MirahezeFunctions::CACHE_DIRECTORY . "/$dbname.php";
		if ( file_exists( $cacheFile ) ) {
			@unlink( $cacheFile );
		}
	}

	/**
	 * Hook: CreateWikiPhpBuilder
	 *
	 * Injects the 'v' (version) key from wikiVersions.php overrides into the
	 * CreateWiki databases cache so MirahezeFunctions::getMediaWikiVersion()
	 * can find it without reading wikiVersions.php on every request.
	 * Overrides any version set via wiki_extra / setExtraFieldData.
	 */
	public static function onCreateWikiPhpBuilder(
		string $dbname,
		array &$cacheData
	): void {
		$versions = @include self::VERSIONS_FILE;
		if ( is_array( $versions ) && isset( $versions[$dbname] ) ) {
			$cacheData['v'] = $versions[$dbname];
		}
	}
}
