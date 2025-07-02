<?php
/**
 * Shortcode class for the Client Testimonials plugin.
 *
 * Handles the [show_testimonials] shortcode.
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
 * Class Shortcode
 *
 * @package CT
 */
class Shortcode {

	/**
	 * Shortcode tag.
	 *
	 * @var string
	 */
	private $shortcode_tag = 'show_testimonials';

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// No specific actions needed in constructor for now.
	}

	/**
	 * Initializes the shortcode.
	 * Hooks the registration method to the 'init' action.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'init', array( $this, 'register_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Registers the [show_testimonials] shortcode.
	 *
	 * @since 1.0.0
	 */
	public function register_shortcode() {
		add_shortcode( $this->shortcode_tag, array( $this, 'render_shortcode' ) );
	}

	/**
	 * Enqueues front-end styles for the shortcode.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		// Check if the shortcode is present on the page before enqueuing.
		// This is a basic check; more robust checks might involve parsing post content.
		global $post;
		if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, $this->shortcode_tag ) ) {
			wp_enqueue_style(
				'ct-shortcode-styles',
				CT_PLUGIN_URL . 'assets/css/client-testimonials.css',
				array(),
				CT_VERSION
			);
		}
	}

	/**
	 * Renders the shortcode output.
	 *
	 * @param array $atts Shortcode attributes (not used in this version).
	 * @return string The shortcode HTML output.
	 * @since 1.0.0
	 */
	public function render_shortcode( $atts ) {
		// Ensure $atts is an array, even if no attributes are passed.
		$atts = shortcode_atts(
			array(
				// Define default attributes here if needed in the future.
				// 'order' => 'DESC',
				// 'orderby' => 'date',
				// 'posts_per_page' => -1,
			),
			$atts,
			$this->shortcode_tag
		);

		$args = array(
			'post_type'      => 'client_testimonial',
			'post_status'    => 'publish',
			'posts_per_page' => -1, // Show all testimonials.
			'orderby'        => 'date',
			'order'          => 'DESC',
		);

		$testimonials_query = new \WP_Query( $args );
		$output             = '';

		if ( $testimonials_query->have_posts() ) {
			$output .= '<div class="ct-testimonials">';

			while ( $testimonials_query->have_posts() ) {
				$testimonials_query->the_post();
				$post_id = get_the_ID();
				$title   = get_the_title();
				$content = get_the_content(); // Already processed by WordPress for block editor content.
				                               // For classic editor, apply the_content filter if needed.
				$rating  = get_post_meta( $post_id, '_ct_star_rating', true );

				$output .= '<div class="ct-testimonial">';
				$output .= '<h3 class="ct-testimonial-title">' . esc_html( $title ) . '</h3>';

				if ( ! empty( $rating ) && absint( $rating ) > 0 ) {
					$output .= '<div class="ct-testimonial-rating">';
					for ( $i = 1; $i <= 5; $i++ ) {
						if ( $i <= absint( $rating ) ) {
							$output .= '<span class="dashicons dashicons-star-filled"></span>';
						} else {
							$output .= '<span class="dashicons dashicons-star-empty"></span>';
						}
					}
					$output .= ' (' . esc_html( absint( $rating ) ) . '/5)';
					$output .= '</div>';
				}

				$output .= '<div class="ct-testimonial-content">' . wp_kses_post( $content ) . '</div>'; // Use wp_kses_post for content.
				$output .= '</div>'; // .ct-testimonial
			}
			wp_reset_postdata(); // Restore original post data.
			$output .= '</div>'; // .ct-testimonials
		} else {
			$output .= '<p>' . esc_html__( 'No testimonials found.', 'client-testimonials' ) . '</p>';
		}

		return $output;
	}
}
