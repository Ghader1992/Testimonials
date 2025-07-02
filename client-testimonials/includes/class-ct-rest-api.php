<?php
/**
 * REST API class for the Client Testimonials plugin.
 *
 * Handles the custom REST API endpoint for testimonials.
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
 * Class Rest_Api
 *
 * @package CT
 */
class Rest_Api {

	/**
	 * The namespace for the REST API.
	 *
	 * @var string
	 */
	protected $namespace = 'ct/v1';

	/**
	 * Route base for testimonials.
	 *
	 * @var string
	 */
	protected $rest_base = 'testimonials';

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// No specific actions needed in constructor for now.
	}

	/**
	 * Initializes the REST API endpoint.
	 * Hooks the route registration to 'rest_api_init'.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Registers the REST API routes.
	 *
	 * @since 1.0.0
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => $this->get_collection_params(),
				),
				'schema' => array( $this, 'get_public_item_schema' ),
			)
		);
	}

	/**
	 * Callback for the /testimonials GET endpoint.
	 * Retrieves a collection of testimonials.
	 *
	 * @param \WP_REST_Request $request The REST API request.
	 * @return \WP_REST_Response|\WP_Error The REST API response.
	 * @since 1.0.0
	 */
	public function get_items( $request ) {
		$args = array(
			'post_type'      => 'client_testimonial',
			'post_status'    => 'publish',
			'posts_per_page' => -1, // Consider adding pagination parameters from $request.
			'orderby'        => 'date', // Default order.
			'order'          => 'DESC',  // Default order.
		);

		// Example: Allow ordering via request parameters.
		$params = $request->get_params();
		if ( isset( $params['orderby'] ) && in_array( strtolower( $params['orderby'] ), array( 'date', 'title', 'id', 'rand' ), true ) ) {
			$args['orderby'] = sanitize_key( $params['orderby'] );
		}
		if ( isset( $params['order'] ) && in_array( strtoupper( $params['order'] ), array( 'ASC', 'DESC' ), true ) ) {
			$args['order'] = sanitize_key( strtoupper( $params['order'] ) );
		}
		if ( isset( $params['per_page'] ) ) {
			$args['posts_per_page'] = absint( $params['per_page'] );
		}
		if ( isset( $params['page'] ) ) {
			$args['paged'] = absint( $params['page'] );
		}


		$query = new \WP_Query( $args );

		$testimonials_data = array();
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_id = get_the_ID();
				$data    = $this->prepare_item_for_response( $post_id, $request );
				if ( ! is_wp_error( $data ) ) {
					$testimonials_data[] = $data;
				}
			}
			wp_reset_postdata();
		}

		$response = rest_ensure_response( $testimonials_data );

		// Add pagination headers if using pagination.
		// $total_posts = $query->found_posts;
		// $max_pages = $query->max_num_pages;
		// $response->header( 'X-WP-Total', (int) $total_posts );
		// $response->header( 'X-WP-TotalPages', (int) $max_pages );


		return $response;
	}

	/**
	 * Prepares a single testimonial output for response.
	 *
	 * @param int              $post_id Post ID.
	 * @param \WP_REST_Request $request Request object.
	 * @return array|\WP_Error Array of item data, or WP_Error.
	 */
	protected function prepare_item_for_response( $post_id, $request ) {
		$post = get_post( $post_id );
		if ( ! $post ) {
			return new \WP_Error( 'rest_post_invalid_id', __( 'Invalid post ID.', 'client-testimonials' ), array( 'status' => 404 ) );
		}

		$rating_raw = get_post_meta( $post->ID, '_ct_star_rating', true );
		$rating     = ! empty( $rating_raw ) ? absint( $rating_raw ) : null;
		// Ensure rating is between 1 and 5, or null if not set or 0.
		if ( null !== $rating && ( $rating < 1 || $rating > 5 ) ) {
			$rating = null;
		}


		$data = array(
			'id'      => $post->ID,
			'title'   => sanitize_text_field( $post->post_title ),
			'content' => wp_kses_post( $post->post_content ), // Sanitized content.
			'rating'  => $rating,
			'date'    => mysql_to_rfc3339( $post->post_date_gmt ), // Or $post->post_date for local time.
		);

		// Contextualize the response.
		$context = ! empty( $request['context'] ) ? $request['context'] : 'view';
		$data    = $this->add_additional_fields_to_object( $data, $request );
		$data    = $this->filter_response_by_context( $data, $context );


		return $data;
	}


	/**
	 * Permission callback for the /testimonials GET endpoint.
	 * Allows public read access.
	 *
	 * @param \WP_REST_Request $request The REST API request.
	 * @return bool True if the request is allowed, false otherwise.
	 * @since 1.0.0
	 */
	public function get_items_permissions_check( $request ) {
		return true; // Publicly accessible as per requirements.
	}

	/**
	 * Retrieves the query params for collections.
	 *
	 * @since 1.0.0
	 * @return array Collection parameters.
	 */
	public function get_collection_params() {
		return array(
			'context'  => array(
				'default'     => 'view',
				'description' => __( 'Scope under which the request is made; determines fields present in response.', 'client-testimonials' ),
				'enum'        => array( 'view', 'embed', 'edit' ),
				'readonly'    => true,
				'type'        => 'string',
			),
			'page'     => array(
				'default'     => 1,
				'description' => __( 'Current page of the collection.', 'client-testimonials' ),
				'minimum'     => 1,
				'type'        => 'integer',
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
			),
			'per_page' => array(
				'default'     => 10,
				'description' => __( 'Maximum number of items to be returned in result set.', 'client-testimonials' ),
				'minimum'     => 1,
				'maximum'     => 100, // Set a reasonable maximum.
				'type'        => 'integer',
				'sanitize_callback' => 'absint',
				'validate_callback' => 'rest_validate_request_arg',
			),
			'order'    => array(
				'default'     => 'desc',
				'description' => __( 'Order sort attribute ascending or descending.', 'client-testimonials' ),
				'enum'        => array( 'asc', 'desc' ),
				'type'        => 'string',
				'sanitize_callback' => 'sanitize_key',
				'validate_callback' => 'rest_validate_request_arg',
			),
			'orderby'  => array(
				'default'     => 'date',
				'description' => __( 'Sort collection by object attribute.', 'client-testimonials' ),
				'enum'        => array( 'date', 'id', 'include', 'title', 'slug', 'modified', 'rand' ),
				'type'        => 'string',
				'sanitize_callback' => 'sanitize_key',
				'validate_callback' => 'rest_validate_request_arg',
			),
		);
	}

	/**
	 * Retrieves the testimonial's schema, conforming to JSON Schema.
	 *
	 * @since 1.0.0
	 * @return array Item schema data.
	 */
	public function get_public_item_schema() {
		if ( isset( $this->schema ) && $this->schema ) {
			return $this->schema;
		}

		$this->schema = array(
			'$schema'    => 'http://json-schema.org/draft-04/schema#',
			'title'      => 'testimonial',
			'type'       => 'object',
			'properties' => array(
				'id'      => array(
					'description' => __( 'Unique identifier for the object.', 'client-testimonials' ),
					'type'        => 'integer',
					'context'     => array( 'view', 'edit', 'embed' ),
					'readonly'    => true,
				),
				'title'   => array(
					'description' => __( 'The title for the object.', 'client-testimonials' ),
					'type'        => 'string', // Should be object to match core, but string for simplicity here.
					                // 'properties'  => array(
					                // 'raw'       => array(
					                // 'description' => __( 'Title for the object, as it exists in the database.', 'client-testimonials' ),
					                // 'type'        => 'string',
					                // 'context'     => array( 'edit' ),
					                // ),
					                // 'rendered'  => array(
					                // 'description' => __( 'HTML title for the object, transformed for display.', 'client-testimonials' ),
					                // 'type'        => 'string',
					                // 'context'     => array( 'view', 'edit', 'embed' ),
					                // 'readonly'    => true,
					                // ),
					                // ),
					'context'     => array( 'view', 'edit', 'embed' ),
				),
				'content' => array(
					'description' => __( 'The content for the object.', 'client-testimonials' ),
					'type'        => 'string', // As above, could be an object with raw/rendered.
					'context'     => array( 'view', 'edit' ),
				),
				'rating'  => array(
					'description' => __( 'Star rating for the testimonial (1-5).', 'client-testimonials' ),
					'type'        => array( 'integer', 'null' ),
					'minimum'     => 1,
					'maximum'     => 5,
					'context'     => array( 'view', 'edit', 'embed' ),
				),
				'date'    => array(
					'description' => __( "The date the object was published, in your site's timezone.", 'client-testimonials' ),
					'type'        => array( 'string', 'null' ),
					'format'      => 'date-time',
					'context'     => array( 'view', 'edit', 'embed' ),
					'readonly'    => true,
				),
			),
		);
		return $this->schema;
	}

	/**
	 * Placeholder for add_additional_fields_to_object if needed by WP_REST_Controller.
	 *
	 * @param array            $object  Object data.
	 * @param \WP_REST_Request $request Request object.
	 * @return array Filtered object data.
	 */
	protected function add_additional_fields_to_object( $object, $request ) {
		return $object;
	}

	/**
	 * Placeholder for filter_response_by_context if needed by WP_REST_Controller.
	 *
	 * @param array  $data    Object data.
	 * @param string $context Request context.
	 * @return array Filtered object data.
	 */
	protected function filter_response_by_context( $data, $context ) {
		return $data;
	}
}
