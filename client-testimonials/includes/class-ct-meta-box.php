<?php
/**
 * Meta Box class for the Client Testimonials plugin.
 *
 * Handles the 'Star Rating' meta box for testimonials.
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
 * Class Meta_Box
 *
 * @package CT
 */
class Meta_Box {

	/**
	 * Meta key for star rating.
	 *
	 * @var string
	 */
	private $meta_key = '_ct_star_rating';

	/**
	 * Nonce action name.
	 *
	 * @var string
	 */
	private $nonce_action = 'ct_save_star_rating_nonce';

	/**
	 * Nonce field name.
	 *
	 * @var string
	 */
	private $nonce_field = 'ct_star_rating_nonce_field';

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// No specific actions needed in constructor for now.
	}

	/**
	 * Initializes the meta box functionality.
	 * Hooks methods to WordPress actions.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'add_meta_boxes_client_testimonial', array( $this, 'add_meta_box' ) );
		add_action( 'save_post_client_testimonial', array( $this, 'save_meta_data' ) );
	}

	/**
	 * Adds the meta box to the 'client_testimonial' post type edit screen.
	 *
	 * @since 1.0.0
	 */
	public function add_meta_box() {
		add_meta_box(
			'ct_star_rating_meta_box',                 // ID
			__( 'Star Rating', 'client-testimonials' ), // Title
			array( $this, 'render_meta_box' ),         // Callback
			'client_testimonial',                      // Screen
			'side',                                    // Context (normal, side, advanced)
			'default'                                  // Priority (high, core, default, low)
		);
	}

	/**
	 * Renders the meta box content.
	 *
	 * @param \WP_Post $post The post object.
	 * @since 1.0.0
	 */
	public function render_meta_box( $post ) {
		// Add a nonce field so we can check for it later.
		wp_nonce_field( $this->nonce_action, $this->nonce_field );

		// Retrieve the current value of the meta field.
		$current_rating = get_post_meta( $post->ID, $this->meta_key, true );
		$current_rating = ! empty( $current_rating ) ? absint( $current_rating ) : 0;

		?>
		<p>
			<label for="ct_star_rating"><?php esc_html_e( 'Rating:', 'client-testimonials' ); ?></label>
			<select name="ct_star_rating" id="ct_star_rating" class="postbox">
				<option value="0" <?php selected( $current_rating, 0 ); ?>><?php esc_html_e( '-- Select a Rating --', 'client-testimonials' ); ?></option>
				<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
					<option value="<?php echo esc_attr( $i ); ?>" <?php selected( $current_rating, $i ); ?>>
						<?php echo esc_html( sprintf( _n( '%s Star', '%s Stars', $i, 'client-testimonials' ), $i ) ); ?>
					</option>
				<?php endfor; ?>
			</select>
		</p>
		<?php
	}

	/**
	 * Saves the meta box data when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 * @since 1.0.0
	 * @return void
	 */
	public function save_meta_data( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST[ $this->nonce_field ] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $this->nonce_field ] ) ), $this->nonce_action ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Make sure that the 'ct_star_rating' field is set.
		if ( ! isset( $_POST['ct_star_rating'] ) ) {
			return;
		}

		// Sanitize user input.
		$rating = sanitize_text_field( wp_unslash( $_POST['ct_star_rating'] ) );
		$rating = absint( $rating ); // Ensure it's a positive integer.

		// Validate the input (must be between 0 and 5, 0 means no rating).
		if ( $rating < 0 || $rating > 5 ) {
			// If validation fails, perhaps set a default or remove if that's preferred.
			// For now, we'll just not update if it's out of range after sanitization.
			// Or, more strictly, if it was an invalid selection.
			// Since absint turns negatives to positives, we mostly care about > 5.
			// A value of 0 is acceptable for "no rating".
			return;
		}

		// Update the meta field in the database.
		if ( $rating > 0 ) {
			update_post_meta( $post_id, $this->meta_key, $rating );
		} else {
			// If rating is 0 (or invalid), delete the meta key.
			delete_post_meta( $post_id, $this->meta_key );
		}
	}
}
