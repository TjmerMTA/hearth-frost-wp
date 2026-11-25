<?php
/**
 * Small helpers used by the templates.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Customizer value with the theme default. */
function hf_mod( $key ) {
	$d = hf_defaults();
	$v = get_theme_mod( 'hf_' . $key, isset( $d[ $key ] ) ? $d[ $key ] : '' );
	return is_string( $v ) ? $v : '';
}

/** Replace {{placeholders}} with company data. */
function hf_fill( $text ) {
	$map = array(
		'{{company}}' => hf_mod( 'company_long' ),
		'{{phone}}'   => hf_mod( 'phone' ),
		'{{email}}'   => hf_mod( 'email' ),
		'{{address}}' => hf_mod( 'address' ),
		'{{region}}'  => hf_mod( 'region' ),
		'{{site}}'    => untrailingslashit( home_url() ),
		'{{updated}}' => get_post() ? get_the_modified_date( 'F j, Y' ) : date_i18n( 'F j, Y' ),
	);
	return strtr( (string) $text, $map );
}

/** Plain text with placeholders filled, escaped. */
function hf_text( $key ) {
	return esc_html( hf_fill( hf_mod( $key ) ) );
}

/** Heading: *word* becomes an italic accent. */
function hf_heading( $key_or_text, $is_text = false ) {
	$t = $is_text ? $key_or_text : hf_mod( $key_or_text );
	$t = esc_html( hf_fill( $t ) );
	return preg_replace( '/\*(.+?)\*/', '<em>$1</em>', $t );
}

/** Image URL: a Media Library URL, or a file name from the theme's assets/img. */
function hf_img( $key_or_file, $is_file = false ) {
	$v = $is_file ? $key_or_file : hf_mod( $key_or_file );
	if ( '' === $v ) {
		return '';
	}
	if ( preg_match( '#^(https?:)?//|^/#', $v ) ) {
		return esc_url( $v );
	}
	return esc_url( HF_URI . '/assets/img/' . ltrim( $v, '/' ) );
}

/** "a|b" lines → [ [a,b], … ]. */
function hf_lines( $key ) {
	$out = array();
	foreach ( preg_split( '/\r\n|\r|\n/', hf_fill( hf_mod( $key ) ) ) as $line ) {
		$line = trim( $line );
		if ( '' === $line ) {
			continue;
		}
		$out[] = array_map( 'trim', explode( '|', $line, 2 ) ) + array( '', '' );
	}
	return $out;
}

function hf_tel() {
	return 'tel:' . preg_replace( '/[^0-9+]/', '', hf_mod( 'phone' ) );
}

/** Page URL by template file, falling back to a slug. */
function hf_page_url( $template, $slug ) {
	static $cache = array();
	if ( ! isset( $cache[ $template ] ) ) {
		$p = get_posts(
			array(
				'post_type'      => 'page',
				'meta_key'       => '_wp_page_template', // phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_value'     => $template, // phpcs:ignore WordPress.DB.SlowDBQuery
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		$cache[ $template ] = $p ? get_permalink( $p[0] ) : home_url( '/' . $slug . '/' );
	}
	return $cache[ $template ];
}

function hf_schedule_url() {
	return hf_page_url( 'template-schedule.php', 'schedule-service' );
}

/** True when the page opens with a full-bleed dark photo (header goes light over it). */
function hf_has_dark_hero() {
	if ( is_front_page() || is_singular( 'hf_service' ) || is_post_type_archive( 'hf_service' ) ) {
		return true;
	}
	if ( is_page_template( array( 'template-about.php', 'template-areas.php' ) ) ) {
		return true;
	}
	return is_page() && has_post_thumbnail() && ! is_page_template( 'template-schedule.php' );
}

/** Inline SVG icons (stroke, 24×24). */
function hf_icon( $name ) {
	$p = array(
		'arrow' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'phone' => '<path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a2 2 0 0 1-2 2A16 16 0 0 1 3 6a2 2 0 0 1 2-2"/>',
		'pin'   => '<path d="M12 21s-7-6.2-7-12a7 7 0 0 1 14 0c0 5.8-7 12-7 12z"/><circle cx="12" cy="9" r="2.5"/>',
		'clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/>',
		'mail'  => '<rect x="3" y="5" width="18" height="14" rx="1"/><path d="m3 7 9 6 9-6"/>',
		'plus'  => '<path d="M12 5v14M5 12h14"/>',
		'menu'  => '<path d="M4 8h16M4 16h16"/>',
		'close' => '<path d="M6 6l12 12M18 6 6 18"/>',
		'check' => '<path d="m5 12 5 5 9-10"/>',
	);
	if ( ! isset( $p[ $name ] ) ) {
		return '';
	}
	return '<svg class="icon icon-' . esc_attr( $name ) . '" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $p[ $name ] . '</svg>';
}

/** Read a bundled content file, split "<!-- key: value -->" header lines from the body. */
function hf_content_file( $rel ) {
	$path = HF_DIR . '/content/' . $rel;
	if ( ! file_exists( $path ) ) {
		return array( 'meta' => array(), 'body' => '' );
	}
	$raw  = file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$meta = array();
	while ( preg_match( '/^\s*<!--\s*(\w+)\s*:\s*(.*?)\s*-->\s*/s', $raw, $m ) ) {
		$meta[ strtolower( $m[1] ) ] = $m[2];
		$raw                         = substr( $raw, strlen( $m[0] ) );
	}
	return array( 'meta' => $meta, 'body' => trim( $raw ) );
}

/** Fallback menu (before a menu is assigned): pages in a sensible order. */
function hf_fallback_menu() {
	$items = array(
		array( get_post_type_archive_link( 'hf_service' ), 'Services' ),
		array( hf_page_url( 'template-about.php', 'about' ), 'About' ),
		array( hf_page_url( 'template-areas.php', 'service-areas' ), 'Service Areas' ),
		array( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' ), 'Journal' ),
		array( hf_page_url( 'template-schedule.php', 'schedule-service' ), 'Contact' ),
	);
	echo '<ul class="menu">';
	foreach ( $items as $i ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $i[0] ), esc_html( $i[1] ) );
	}
	echo '</ul>';
}
