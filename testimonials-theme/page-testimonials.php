<?php
/**
 * Template Name: Testimonials Page
 *
 * This is the template that displays testimonials using the [show_testimonials] shortcode.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Testimonials_Theme
 * @since 1.0.0
 */

// If get_header and get_footer are not defined by functions.php (e.g. very minimal theme),
// you might need to include them directly or ensure functions.php defines them.
// For this project, functions.php provides basic get_header() and get_footer().

get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<?php
					// Display page content (if any, added above the shortcode in the editor)
					the_content();

					// Execute the shortcode to display testimonials
					if ( shortcode_exists( 'show_testimonials' ) ) {
						echo do_shortcode( '[show_testimonials]' );
					} else {
						echo '<p>' . esc_html__( 'Client Testimonials plugin is not active or the shortcode is not registered.', 'testimonials-theme' ) . '</p>';
					}
					?>
				</div><!-- .entry-content -->

				<?php if ( get_edit_post_link() ) : ?>
					<footer class="entry-footer">
						<?php
						edit_post_link(
							sprintf(
								wp_kses(
									/* translators: %s: Name of current post. Only visible to screen readers */
									__( 'Edit <span class="screen-reader-text">%s</span>', 'testimonials-theme' ),
									array(
										'span' => array(
											'class' => array(),
										),
									)
								),
								get_the_title()
							),
							'<span class="edit-link">',
							'</span>'
						);
						?>
					</footer><!-- .entry-footer -->
				<?php endif; ?>
			</article><!-- #post-<?php the_ID(); ?> -->

			<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #primary -->

<?php
get_footer();
?>
