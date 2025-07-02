<?php
/**
 * Loader class for the Client Testimonials plugin.
 *
 * Initializes the plugin and loads its components.
 *
 * @package ClientTestimonials
 * @since   1.0.0
 */

namespace CT;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Loader
 *
 * @package CT
 */
class Loader {

	/**
	 * Stores instances of classes.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	private $instances = array();

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->init_classes();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		// Classes are already loaded by the autoloader in the main plugin file.
		// This method can be used for other types of dependencies if needed in the future.
	}

	/**
	 * Initialize classes and hook them into WordPress.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function init_classes() {
		$this->instances['post_type'] = new Post_Type();
		$this->instances['post_type']->init();

		$this->instances['meta_box'] = new Meta_Box();
		$this->instances['meta_box']->init();

		$this->instances['shortcode'] = new Shortcode();
		$this->instances['shortcode']->init();

		$this->instances['rest_api'] = new Rest_Api();
		$this->instances['rest_api']->init();

		// Add other class initializations here.
		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			'client-testimonials',
			false,
			dirname( plugin_basename( CT_PLUGIN_DIR ) ) . '/languages/'
		);
	}

	/**
	 * Run the loader.
	 *
	 * This method is called from the main plugin file to start the plugin.
	 * Currently, the constructor handles all initialization.
	 *
	 * @since 1.0.0
	 */
	public function run() {
		// The `run` method in `client-testimonials.php` calls this.
		// All major initialization is done in the constructor.
	}
}
