<?php
/**
 * Post Type class for the Client Testimonials plugin.
 *
 * Handles the registration of the 'client_testimonial' custom post type.
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
 * Class Post_Type
 *
 * @package CT
 */
class Post_Type {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of this plugin.
	 */
	protected $version;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'client-testimonials'; // Or retrieve dynamically if needed.
		$this->version     = CT_VERSION; // Assuming CT_VERSION is defined.
	}

	/**
	 * Initializes the post type.
	 * Hooks the registration method to the 'init' action.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	/**
	 * Registers the 'client_testimonial' custom post type.
	 *
	 * @since 1.0.0
	 */
	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Testimonials', 'Post Type General Name', 'client-testimonials' ),
			'singular_name'         => _x( 'Testimonial', 'Post Type Singular Name', 'client-testimonials' ),
			'menu_name'             => __( 'Testimonials', 'client-testimonials' ),
			'name_admin_bar'        => __( 'Testimonial', 'client-testimonials' ),
			'archives'              => __( 'Testimonial Archives', 'client-testimonials' ),
			'attributes'            => __( 'Testimonial Attributes', 'client-testimonials' ),
			'parent_item_colon'     => __( 'Parent Testimonial:', 'client-testimonials' ),
			'all_items'             => __( 'All Testimonials', 'client-testimonials' ),
			'add_new_item'          => __( 'Add New Testimonial', 'client-testimonials' ),
			'add_new'               => __( 'Add New', 'client-testimonials' ),
			'new_item'              => __( 'New Testimonial', 'client-testimonials' ),
			'edit_item'             => __( 'Edit Testimonial', 'client-testimonials' ),
			'update_item'           => __( 'Update Testimonial', 'client-testimonials' ),
			'view_item'             => __( 'View Testimonial', 'client-testimonials' ),
			'view_items'            => __( 'View Testimonials', 'client-testimonials' ),
			'search_items'          => __( 'Search Testimonial', 'client-testimonials' ),
			'not_found'             => __( 'Not found', 'client-testimonials' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'client-testimonials' ),
			'featured_image'        => __( 'Featured Image', 'client-testimonials' ),
			'set_featured_image'    => __( 'Set featured image', 'client-testimonials' ),
			'remove_featured_image' => __( 'Remove featured image', 'client-testimonials' ),
			'use_featured_image'    => __( 'Use as featured image', 'client-testimonials' ),
			'insert_into_item'      => __( 'Insert into testimonial', 'client-testimonials' ),
			'uploaded_to_this_item' => __( 'Uploaded to this testimonial', 'client-testimonials' ),
			'items_list'            => __( 'Testimonials list', 'client-testimonials' ),
			'items_list_navigation' => __( 'Testimonials list navigation', 'client-testimonials' ),
			'filter_items_list'     => __( 'Filter testimonials list', 'client-testimonials' ),
		);
		$args   = array(
			'label'               => __( 'Testimonial', 'client-testimonials' ),
			'description'         => __( 'Client testimonials and reviews.', 'client-testimonials' ),
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-testimonial',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'post',
			'show_in_rest'        => false, // As per requirements.
		);
		register_post_type( 'client_testimonial', $args );
	}
}
