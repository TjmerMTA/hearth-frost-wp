<?php
/**
 * First run: fill the site with the demo content once (pages, services, reviews, journal, menus).
 * Runs only while the option "hf_seeded" is absent; deleting content later does not bring it back.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	if ( get_option( 'hf_seeded' ) ) {
		return;
	}
	update_option( 'hf_seeded', HF_VER, false );
	hf_seed();
}, 20 );

/** Copy a theme picture into the Media Library (no resized copies, so it is fast); return attachment id. */
function hf_seed_image( $file, $title ) {
	static $cache = array();
	if ( isset( $cache[ $file ] ) ) {
		return $cache[ $file ];
	}
	$path = HF_DIR . '/assets/img/' . $file;
	if ( ! file_exists( $path ) ) {
		return 0;
	}
	$up = wp_upload_bits( $file, null, file_get_contents( $path ) ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( ! empty( $up['error'] ) ) {
		return 0;
	}
	$id = wp_insert_attachment(
		array(
			'post_mime_type' => 'image/jpeg',
			'post_title'     => $title,
			'post_status'    => 'inherit',
		),
		$up['file']
	);
	if ( $id && ! is_wp_error( $id ) ) {
		$size = @getimagesize( $up['file'] ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		wp_update_attachment_metadata(
			$id,
			array(
				'width'  => $size ? $size[0] : 0,
				'height' => $size ? $size[1] : 0,
				'file'   => _wp_relative_upload_path( $up['file'] ),
				'sizes'  => array(),
			)
		);
		update_post_meta( $id, '_wp_attachment_image_alt', $title );
	}
	$cache[ $file ] = (int) $id;
	return $cache[ $file ];
}

function hf_seed_page( $title, $slug, $content = '', $template = '', $image = '' ) {
	$existing = get_page_by_path( $slug );
	$args     = array(
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_title'   => $title,
		'post_name'    => $slug,
		'post_content' => $content,
	);
	if ( $existing ) {
		$args['ID'] = $existing->ID;
		$id         = wp_update_post( $args );
	} else {
		$id = wp_insert_post( $args );
	}
	if ( ! $id || is_wp_error( $id ) ) {
		return 0;
	}
	if ( $template ) {
		update_post_meta( $id, '_wp_page_template', $template );
	}
	if ( $image ) {
		$img = hf_seed_image( $image, $title );
		if ( $img ) {
			set_post_thumbnail( $id, $img );
		}
	}
	return (int) $id;
}

function hf_seed() {
	// Clean slate: retire WordPress's sample post and page (only the untouched defaults).
	foreach ( array( array( 'hello-world', 'post' ), array( 'sample-page', 'page' ) ) as $s ) {
		$p = get_page_by_path( $s[0], OBJECT, $s[1] );
		if ( $p ) {
			wp_trash_post( $p->ID );
		}
	}

	update_option( 'blogname', hf_mod( 'company' ) );
	update_option( 'blogdescription', hf_mod( 'tagline' ) );

	// Services.
	$service_ids = array();
	foreach ( hf_default_services() as $slug => $s ) {
		$c = hf_content_file( 'services/' . $slug . '.html' );
		if ( '' === $c['body'] ) {
			continue;
		}
		$title = isset( $c['meta']['title'] ) ? $c['meta']['title'] : ucwords( str_replace( '-', ' ', $slug ) );
		$id    = wp_insert_post(
			array(
				'post_type'    => 'hf_service',
				'post_status'  => 'publish',
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_excerpt' => isset( $c['meta']['excerpt'] ) ? $c['meta']['excerpt'] : '',
				'post_content' => $c['body'],
				'menu_order'   => $s[1],
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			$img = hf_seed_image( $s[0], $title );
			if ( $img ) {
				set_post_thumbnail( $id, $img );
			}
			$service_ids[] = $id;
		}
	}

	// Reviews.
	foreach ( hf_default_reviews() as $i => $r ) {
		$id = wp_insert_post(
			array(
				'post_type'    => 'hf_review',
				'post_status'  => 'publish',
				'post_title'   => $r[1],
				'post_content' => $r[0],
				'menu_order'   => $i + 1,
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_hf_place', $r[2] );
		}
	}

	// Journal posts.
	for ( $i = 1; $i <= 3; $i++ ) {
		$c = hf_content_file( 'posts/' . $i . '.html' );
		if ( '' === $c['body'] ) {
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_type'    => 'post',
				'post_status'  => 'publish',
				'post_title'   => isset( $c['meta']['title'] ) ? $c['meta']['title'] : 'Maintenance tip ' . $i,
				'post_excerpt' => isset( $c['meta']['excerpt'] ) ? $c['meta']['excerpt'] : '',
				'post_content' => $c['body'],
				'post_date'    => gmdate( 'Y-m-d H:i:s', time() - ( 4 - $i ) * 9 * DAY_IN_SECONDS ),
			)
		);
		if ( $id && ! is_wp_error( $id ) && ! empty( $c['meta']['image'] ) ) {
			$img = hf_seed_image( sanitize_file_name( $c['meta']['image'] ) . '.jpg', get_the_title( $id ) );
			if ( $img ) {
				set_post_thumbnail( $id, $img );
			}
		}
	}

	// Pages.
	$home    = hf_seed_page( 'Home', 'home', '', '' );
	$journal = hf_seed_page( 'Journal', 'journal' );
	$about_c = hf_content_file( 'about.html' );
	$about   = hf_seed_page( 'About', 'about', $about_c['body'], 'template-about.php', 'kitchen-dark-2.jpg' );
	$areas   = hf_seed_page( 'Service Areas', 'service-areas', '', 'template-areas.php', 'open-kitchen.jpg' );
	$sched   = hf_seed_page( 'Schedule Service', 'schedule-service', '', 'template-schedule.php' );
	$terms_c = hf_content_file( 'legal/terms-of-use.html' );
	$terms   = hf_seed_page( 'Terms of Use', 'terms-of-use', $terms_c['body'] );

	// Privacy Policy: reuse the page WordPress already created, so Settings → Privacy points to it.
	$priv_c  = hf_content_file( 'legal/privacy-policy.html' );
	$priv_id = (int) get_option( 'wp_page_for_privacy_policy' );
	if ( $priv_id && get_post( $priv_id ) ) {
		wp_update_post(
			array(
				'ID'           => $priv_id,
				'post_title'   => 'Privacy Policy',
				'post_name'    => 'privacy-policy',
				'post_status'  => 'publish',
				'post_content' => $priv_c['body'],
			)
		);
	} else {
		$priv_id = hf_seed_page( 'Privacy Policy', 'privacy-policy', $priv_c['body'] );
		update_option( 'wp_page_for_privacy_policy', $priv_id );
	}

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home );
	update_option( 'page_for_posts', $journal );

	// Menus.
	$menus = array(
		'primary' => 'Main menu',
		'footer'  => 'Footer — company',
		'legal'   => 'Footer — legal',
	);
	$locs  = array();
	foreach ( $menus as $loc => $name ) {
		$m = wp_get_nav_menu_object( $name );
		$locs[ $loc ] = $m ? $m->term_id : wp_create_nav_menu( $name );
	}
	$add_page = function ( $menu, $page_id, $parent = 0, $title = '' ) {
		return wp_update_nav_menu_item(
			$menu,
			0,
			array(
				'menu-item-object-id' => $page_id,
				'menu-item-object'    => 'page',
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-parent-id' => $parent,
				'menu-item-title'     => $title,
			)
		);
	};
	$m        = $locs['primary'];
	$services = wp_update_nav_menu_item(
		$m,
		0,
		array(
			'menu-item-title'  => 'Services',
			'menu-item-type'   => 'post_type_archive',
			'menu-item-object' => 'hf_service',
			'menu-item-status' => 'publish',
		)
	);
	foreach ( $service_ids as $sid ) {
		wp_update_nav_menu_item(
			$m,
			0,
			array(
				'menu-item-object-id' => $sid,
				'menu-item-object'    => 'hf_service',
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-parent-id' => $services,
			)
		);
	}
	$add_page( $m, $about );
	$add_page( $m, $areas );
	$add_page( $m, $journal );
	$add_page( $m, $sched, 0, 'Contact' );

	$add_page( $locs['footer'], $about );
	$add_page( $locs['footer'], $areas );
	$add_page( $locs['footer'], $journal );
	$add_page( $locs['footer'], $sched );
	$add_page( $locs['legal'], $priv_id );
	$add_page( $locs['legal'], $terms );
	set_theme_mod( 'nav_menu_locations', $locs );

	update_option( 'permalink_structure', '/%postname%/' );
	flush_rewrite_rules( false );
}
