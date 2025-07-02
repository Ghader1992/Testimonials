<?php
/**
 * Testimonials Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Testimonials_Theme
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define constants for the theme.
 */
define( 'TESTIMONIALS_THEME_VERSION', '1.0.0' );
define( 'TESTIMONIALS_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'TESTIMONIALS_THEME_URL', trailingslashit( get_template_directory_uri() ) );


if ( ! function_exists( 'testimonials_theme_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @since 1.0.0
	 */
	function testimonials_theme_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 */
		load_theme_textdomain( 'testimonials-theme', TESTIMONIALS_THEME_DIR . 'languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'testimonials-theme' ),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Set up the WordPress core custom background feature.
		add_theme_support(
			'custom-background',
			apply_filters(
				'testimonials_theme_custom_background_args',
				array(
					'default-color' => 'ffffff',
					'default-image' => '',
				)
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support(
			'custom-logo',
			array(
				'height'      => 250,
				'width'       => 250,
				'flex-width'  => true,
				'flex-height' => true,
			)
		);
	}
endif; // testimonials_theme_setup
add_action( 'after_setup_theme', 'testimonials_theme_setup' );

/**
 * Enqueue scripts and styles.
 *
 * @since 1.0.0
 */
function testimonials_theme_scripts() {
	// Enqueue parent theme stylesheet.
	wp_enqueue_style( 'testimonials-theme-style', get_stylesheet_uri(), array(), TESTIMONIALS_THEME_VERSION );

	// If the Client Testimonials plugin is active and has its CSS, it will be enqueued by the plugin.
	// This theme doesn't need to enqueue it directly, but it's good practice to ensure dependencies are handled.
	// Example: if the plugin has a specific handle 'ct-frontend-styles'
	// if ( wp_style_is( 'ct-shortcode-styles', 'registered' ) ) {
	// wp_enqueue_style( 'ct-shortcode-styles' );
	// }
	// However, the plugin already conditionally enqueues its CSS when the shortcode is present.

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'testimonials_theme_scripts' );

/**
 * Display a basic header.
 *
 * @since 1.0.0
 */
function testimonials_theme_header() {
	?>
	<!doctype html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="profile" href="https://gmpg.org/xfn/11">
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'testimonials-theme' ); ?></a>
		<header id="masthead" class="site-header">
			<div class="site-branding container">
				<?php
				if ( has_custom_logo() ) {
					the_custom_logo();
				} elseif ( is_front_page() && is_home() ) {
					?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php
				} else {
					?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<?php
				}
				$testimonials_theme_description = get_bloginfo( 'description', 'display' );
				if ( $testimonials_theme_description || is_customize_preview() ) :
					?>
					<p class="site-description"><?php echo esc_html( $testimonials_theme_description ); ?></p>
				<?php endif; ?>
			</div><!-- .site-branding -->
		</header><!-- #masthead -->
		<div id="content" class="site-content container">
	<?php
}

/**
 * Display a basic footer.
 *
 * @since 1.0.0
 */
function testimonials_theme_footer() {
	?>
		</div><!-- #content -->
		<footer id="colophon" class="site-footer">
			<div class="site-info container">
				<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'testimonials-theme' ) ); ?>">
					<?php
					/* translators: %s: CMS name, i.e. WordPress. */
					printf( esc_html__( 'Proudly powered by %s', 'testimonials-theme' ), 'WordPress' );
					?>
				</a>
				<span class="sep"> | </span>
				<?php
				/* translators: 1: Theme name, 2: Theme author. */
				printf( esc_html__( 'Theme: %1$s by %2$s.', 'testimonials-theme' ), 'Testimonials Theme', '<a href="https://example.com/">Jules</a>' );
				?>
			</div><!-- .site-info -->
		</footer><!-- #colophon -->
	</div><!-- #page -->
	<?php wp_footer(); ?>
	</body>
	</html>
	<?php
}

// Simple template functions for get_header, get_footer.
if ( ! function_exists( 'get_header' ) ) {
	/**
	 * Loads the theme header.
	 */
	function get_header( $name = null ) {
		do_action( 'get_header', $name );
		testimonials_theme_header();
	}
}

if ( ! function_exists( 'get_footer' ) ) {
	/**
	 * Loads the theme footer.
	 */
	function get_footer( $name = null ) {
		do_action( 'get_footer', $name );
		testimonials_theme_footer();
	}
}

// Basic index.php and page.php content if those files are not created.
// This ensures the theme is technically valid even if minimal.
if ( ! function_exists( 'testimonials_theme_content_loop' ) ) {
	/**
	 * Basic loop for displaying content.
	 */
	function testimonials_theme_content_loop() {
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<?php
						the_content(
							sprintf(
								wp_kses(
									/* translators: %s: Name of current post. Only visible to screen readers */
									__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'testimonials-theme' ),
									array(
										'span' => array(
											'class' => array(),
										),
									)
								),
								get_the_title()
							)
						);

						wp_link_pages(
							array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'testimonials-theme' ),
								'after'  => '</div>',
							)
						);
						?>
					</div><!-- .entry-content -->
				</article><!-- #post-<?php the_ID(); ?> -->
				<?php
			endwhile;
		else :
			?>
			<p><?php esc_html_e( 'Sorry, no posts matched your criteria.', 'testimonials-theme' ); ?></p>
			<?php
		endif;
	}
}

/**
 * Fallback for the_content if it's not used in a template.
 *
 * @param string $content The post content.
 * @return string Processed post content.
 */
function testimonials_theme_default_the_content( $content ) {
    if ( is_main_query() && in_the_loop() && is_page() && ! is_page_template() ) {
        // This is a very basic way to ensure content shows up if no specific template file is hit.
    }
    return $content;
}
// add_filter('the_content', 'testimonials_theme_default_the_content');
// Commented out as page-testimonials.php will handle its content.

// This theme is minimal, so it won't include index.php or page.php to keep it simple.
// The page-testimonials.php template will be the primary focus.
// WordPress will use its default hierarchy if other templates are missing.

?>
