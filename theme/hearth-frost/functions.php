<?php
/**
 * Hearth & Frost — theme bootstrap.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'HF_VER', '1.0.0' );
define( 'HF_DIR', get_template_directory() );
define( 'HF_URI', get_template_directory_uri() );

require HF_DIR . '/inc/defaults.php';
require HF_DIR . '/inc/helpers.php';
require HF_DIR . '/inc/post-types.php';
require HF_DIR . '/inc/customizer.php';
require HF_DIR . '/inc/form.php';
require HF_DIR . '/inc/seed.php';

add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 320, 'flex-width' => true, 'flex-height' => true ) );
	register_nav_menus(
		array(
			'primary' => 'Main menu',
			'footer'  => 'Footer — company links',
			'legal'   => 'Footer — legal links',
		)
	);
} );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'hf-fonts', 'https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;600;700&family=Newsreader:ital,opsz,wght@0,6..72,200;0,6..72,300;0,6..72,400;1,6..72,200;1,6..72,300;1,6..72,400&display=swap', array(), null );
	wp_enqueue_style( 'hf-main', HF_URI . '/assets/css/main.css', array(), HF_VER );
	wp_enqueue_script( 'hf-main', HF_URI . '/assets/js/main.js', array(), HF_VER, true );
	wp_localize_script(
		'hf-main',
		'HF',
		array(
			'ajax'  => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'hf_request' ),
		)
	);
} );

/* Mark JS early so scroll-reveal content never flashes. */
add_action( 'wp_head', function () {
	echo "<script>document.documentElement.classList.add('js')</script>\n";
}, 1 );

add_filter( 'wp_resource_hints', function ( $urls, $type ) {
	if ( 'preconnect' === $type ) {
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}, 10, 2 );

add_filter( 'excerpt_length', function () {
	return 26;
} );
add_filter( 'excerpt_more', function () {
	return '…';
} );

/* Company data in page texts: {{company}}, {{phone}}, {{email}}, {{address}}, {{region}}, {{site}}, {{updated}}
   are filled in when the page is shown, so changing them in Customize updates every page (incl. legal ones). */
add_filter( 'the_content', 'hf_fill', 5 );

/** Body class for pages whose header sits over a dark photo. */
add_filter( 'body_class', function ( $classes ) {
	if ( hf_has_dark_hero() ) {
		$classes[] = 'has-dark-hero';
	}
	return $classes;
} );
