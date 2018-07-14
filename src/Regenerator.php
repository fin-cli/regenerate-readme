<?php
/**
 * PHP Composter action to regenerate the README.md file
 * (pre-commit + post-commit).
 *
 * @package   WP_CLI\RegenerateReadme
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 */

namespace WP_CLI\RegenerateReadme;

use PHPComposter\PHPComposter\BaseAction;
use WP_CLI;

/**
 * Class Regenerator.
 *
 * @since   0.1.0
 *
 * @package WP_CLI\RegenerateReadme
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
final class Regenerator extends BaseAction {

	const MARKER_FILE = '.commit-readme-md';

	/**
	 * Try to regenerate the README.md file and memorize whether changes were
	 * detected.
	 *
	 * @since 0.1.0
	 */
	public function pre_commit() {
		if ( file_exists( self::MARKER_FILE ) ) {
			unlink( self::MARKER_FILE );
		}

		$hash = md5( file_get_contents( 'README.md' ) );
		shell_exec( 'vendor/bin/wp scaffold package-readme . --force > /dev/null 2>&1' );
		if ( $hash === md5( file_get_contents( 'README.md' ) ) ) {
			return;
		}

		shell_exec( 'touch ' . self::MARKER_FILE );
	}

	/**
	 * If the marker file is found, add the modified README.md file to the
	 * commit and amend it.
	 *
	 * @since 0.1.0
	 */
	public function post_commit() {
		if ( file_exists( self::MARKER_FILE ) ) {
			unlink( self::MARKER_FILE );
		}

		shell_exec( 'git add README.md' );
		shell_exec( 'git commit --amend -C HEAD --no-verify' );
	}
}
