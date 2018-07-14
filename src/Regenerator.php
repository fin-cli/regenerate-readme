<?php
/**
 * PHP Composter action to regenerate the README.md file (precommit).
 *
 * @package   WP_CLI\RegenerateReadme
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 */

namespace WP_CLI\RegenerateReadme;

use Exception;
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
class Regenerator extends BaseAction {

	/**
	 * Run PHP Code Sniffer over PHP files as pre-commit hook.
	 *
	 * @since 0.1.0
	 */
	public function preCommit() {

		try {
			ob_start();
			shell_exec( 'vendor/bin/wp scaffold package-readme . --force' );
			ob_clean();
		} catch ( Exception $exception ) {
			echo 'Failed to regenerate README.md file. Aborting commit.' . PHP_EOL;
			exit( 1 );
		}

		shell_exec( 'git add README.md' );
	}
}
