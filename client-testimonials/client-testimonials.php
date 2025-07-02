<?php
/**
 * Plugin Name:       Client Testimonials
 * Plugin URI:        https://example.com/client-testimonials
 * Description:       A plugin to manage and display client testimonials.
 * Version:           1.0.0
 * Author:            Jules
 * Author URI:        https://example.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       client-testimonials
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define constants
 */
define( 'CT_VERSION', '1.0.0' );
define( 'CT_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'CT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Autoloader for PSR-4 compliance.
 *
 * @param string $class The fully-qualified class name.
 * @return void
 */
spl_autoload_register(
	function ( $class ) {
		// Project-specific namespace prefix.
		$prefix = 'CT\\';

		// Base directory for the namespace prefix.
		$base_dir = CT_PLUGIN_DIR . 'includes/';

		// Does the class use the namespace prefix?
		$len = strlen( $prefix );
		if ( strncmp( $prefix, $class, $len ) !== 0 ) {
			// No, move to the next registered autoloader.
			return;
		}

		// Get the relative class name.
		$relative_class = substr( $class, $len );

		// Replace the namespace prefix with the base directory, replace namespace
		// separators with directory separators in the relative class name, append
		// with .php.
		$file = $base_dir . 'class-ct-' . str_replace( '_', '-', strtolower( $relative_class ) ) . '.php';
		$file = str_replace( '\\', '', $file ); // Remove backslash from class name for filename

		// If the file exists, require it.
		if ( file_exists( $file ) ) {
			require $file;
		}
	}
);


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require CT_PLUGIN_DIR . 'includes/class-ct-loader.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function ct_run_plugin() {
	$plugin = new CT\Loader();
	$plugin->run();
}
ct_run_plugin();
