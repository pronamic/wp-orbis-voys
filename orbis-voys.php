<?php
/**
 * Orbis Voys
 *
 * @author    Pronamic
 * @copyright 2005-2026 Pronamic
 * @license   GPL-2.0-or-later
 * @package   Pronamic\Orbis\Voys
 *
 * @wordpress-plugin
 * Plugin Name:       Orbis Voys
 * Plugin URI:        https://wp.pronamic.directory/plugins/orbis-voys/
 * Description:       The Orbis Voys plugin shows Voys phone calls on Orbis persons.
 * Version:           1.0.0
 * Requires at least: 7.1
 * Requires PHP:      8.3
 * Requires Plugins:  orbis-contacts, orbis-persons
 * Author:            Pronamic
 * Author URI:        https://www.pronamic.eu/
 * Text Domain:       orbis-voys
 * Domain Path:       /languages/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://wp.pronamic.directory/plugins/orbis-voys/
 * GitHub URI:        https://github.com/pronamic/wp-orbis-voys
 */

declare(strict_types=1);

namespace Pronamic\Orbis\Voys;

if ( ! \defined( 'ABSPATH' ) ) {
	exit;
}

( static function (): void {
	$autoload_path = __DIR__ . '/vendor/autoload_packages.php';

	if ( \file_exists( $autoload_path ) ) {
		require_once $autoload_path;
	}

	Plugin::instance();
} )();
