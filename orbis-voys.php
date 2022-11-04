<?php
/**
 * Orbis Voys
 *
 * @package           Pronamic\WordPress\Orbis\Voys
 * @author            Pronamic
 * @copyright         2021 Pronamic
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Orbis Voys
 * Plugin URI:        https://www.pronamic.eu/plugins/orbis-voys/
 * Description:       The Orbis Voys plugin can process Voys call notifications and display calls in your Orbis installation.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Pronamic
 * Author URI:        https://www.pronamic.eu/
 * Text Domain:       orbis-voys
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/**
 * Autoload.
 */
require __DIR__ . '/vendor/autoload.php';

/**
 * Action.
 */
add_action( 'orbis_after_side_content', function() {
    if ( ! is_singular( 'orbis_person' ) ) {
        return;
    }

    include __DIR__ . '/templates/person-calls.php';
} );
