<?php
/**
 * Woodstove functions and definitions
 *
 * @package Woodstove
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'woodstove_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function woodstove_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Woodstove, use a find and replace
	 * to change 'woodstove' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'woodstove', get_template_directory() . '/languages' );

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
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'woodstove' ),
		'secondary' => __( 'Secondary Menu', 'woodstove' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'woodstove_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // woodstove_setup
add_action( 'after_setup_theme', 'woodstove_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function woodstove_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'woodstove' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'woodstove_widgets_init' );


/**
 * Enqueue styles.
 */

if ( !function_exists( 'woodstove_styles' ) ) :

	function woodstove_styles() {

		wp_enqueue_style( 'woodstove_styles', get_stylesheet_directory_uri() . '/assets/dist/css/app.css', '', '9' );
		wp_enqueue_style( 'font_awesome_styles', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css', '', '9' );
		wp_enqueue_style( 'theme_styles', get_stylesheet_directory_uri() . '/style.css', '', '9' );

	}

add_action( 'wp_enqueue_scripts', 'woodstove_styles' );

endif;

/**
 * Enqueue scripts.
 */
function woodstove_scripts() {

	// Add core Foundation js to footer
	wp_enqueue_script( 'foundation-js', get_template_directory_uri() . '/node_modules/foundation-sites/dist/foundation.js', array( 'jquery' ), '6.0.3', true );

	// Add our concatenated js file
	if ( WP_DEBUG ) {

		// Enqueue our full version if in development mode
		wp_enqueue_script( 'woodstove_appjs', get_template_directory_uri() . '/assets/dist/js/app.js', array( 'jquery' ), '', true );

	} else {

		// Enqueue minified js if in production mode
		wp_enqueue_script( 'woodstove_appjs', get_template_directory_uri() . '/assets/dist/js/app.min.js', array( 'jquery' ), '', true );
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'woodstove_scripts' );


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';



add_filter( 'wp_nav_menu', 'woodstove_nav_menu', 10, 2 );

function woodstove_nav_menu( $menu ){
	$menu = str_replace('current-menu-item', 'current-menu-item active', $menu);
	return $menu;
}


/**
 * Make oembed elements responsive. Add Foundation's .flex-video class wrapper
 * around any oembeds
 */

add_filter( 'embed_oembed_html', 'woodstove_oembed_flex_wrapper', 10, 4 ) ;

function woodstove_oembed_flex_wrapper( $html, $url, $attr, $post_ID ) {
	$return = '<div class="flex-video">'.$html.'</div>';
	return $return;
}
