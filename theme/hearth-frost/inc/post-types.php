<?php
/**
 * Services, Reviews and Service requests.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', function () {
	register_post_type(
		'hf_service',
		array(
			'labels'        => array(
				'name'          => 'Services',
				'singular_name' => 'Service',
				'add_new_item'  => 'Add service',
				'edit_item'     => 'Edit service',
				'all_items'     => 'All services',
			),
			'public'        => true,
			'has_archive'   => 'services',
			'rewrite'       => array( 'slug' => 'services', 'with_front' => false ),
			'menu_icon'     => 'dashicons-admin-tools',
			'menu_position' => 20,
			'show_in_rest'  => true,
			'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
		)
	);

	register_post_type(
		'hf_review',
		array(
			'labels'        => array(
				'name'          => 'Reviews',
				'singular_name' => 'Review',
				'add_new_item'  => 'Add review',
				'edit_item'     => 'Edit review',
			),
			'public'        => false,
			'show_ui'       => true,
			'menu_icon'     => 'dashicons-format-quote',
			'menu_position' => 21,
			'supports'      => array( 'title', 'editor', 'page-attributes' ),
		)
	);

	register_post_type(
		'hf_request',
		array(
			'labels'        => array(
				'name'          => 'Service requests',
				'singular_name' => 'Service request',
				'menu_name'     => 'Requests',
				'edit_item'     => 'Service request',
			),
			'public'        => false,
			'show_ui'       => true,
			'menu_icon'     => 'dashicons-calendar-alt',
			'menu_position' => 3,
			'capability_type' => 'post',
			'capabilities'  => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'  => true,
			'supports'      => array( 'title', 'editor' ),
		)
	);
} );

/* Review: title = customer name, a "Location" field. */
add_action( 'add_meta_boxes', function () {
	add_meta_box( 'hf_review_place', 'Location / role', function ( $post ) {
		wp_nonce_field( 'hf_review', 'hf_review_nonce' );
		printf(
			'<input type="text" name="hf_place" value="%s" class="widefat" placeholder="e.g. Newport Beach">',
			esc_attr( get_post_meta( $post->ID, '_hf_place', true ) )
		);
		echo '<p class="description">The review text goes in the main editor; the title is the customer name.</p>';
	}, 'hf_review', 'side' );

	add_meta_box( 'hf_request_details', 'Details', function ( $post ) {
		$f = array(
			'name'      => 'Name',
			'phone'     => 'Phone',
			'email'     => 'E-mail',
			'zip'       => 'ZIP code',
			'appliance' => 'Appliance',
			'brand'     => 'Brand',
			'model'     => 'Model / serial',
			'when'      => 'Preferred time',
			'page'      => 'Sent from',
		);
		echo '<table class="widefat striped"><tbody>';
		foreach ( $f as $k => $label ) {
			printf( '<tr><th style="width:160px">%s</th><td>%s</td></tr>', esc_html( $label ), esc_html( get_post_meta( $post->ID, '_hf_' . $k, true ) ) );
		}
		echo '</tbody></table>';
	}, 'hf_request', 'normal', 'high' );
} );

add_action( 'save_post_hf_review', function ( $id ) {
	if ( ! isset( $_POST['hf_review_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hf_review_nonce'] ) ), 'hf_review' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $id ) ) {
		return;
	}
	if ( isset( $_POST['hf_place'] ) ) {
		update_post_meta( $id, '_hf_place', sanitize_text_field( wp_unslash( $_POST['hf_place'] ) ) );
	}
} );

/* Request list columns. */
add_filter( 'manage_hf_request_posts_columns', function ( $c ) {
	return array(
		'cb'        => $c['cb'],
		'title'     => 'Request',
		'appliance' => 'Appliance',
		'zip'       => 'ZIP',
		'date'      => 'Received',
	);
} );
add_action( 'manage_hf_request_posts_custom_column', function ( $col, $id ) {
	if ( in_array( $col, array( 'appliance', 'zip' ), true ) ) {
		echo esc_html( get_post_meta( $id, '_hf_' . $col, true ) );
	}
}, 10, 2 );

/* New-request counter in the menu. */
add_action( 'admin_menu', function () {
	global $menu;
	$n = (int) wp_count_posts( 'hf_request' )->pending;
	if ( ! $n ) {
		return;
	}
	foreach ( $menu as $k => $item ) {
		if ( isset( $item[2] ) && 'edit.php?post_type=hf_request' === $item[2] ) {
			$menu[ $k ][0] .= ' <span class="awaiting-mod"><span class="pending-count">' . $n . '</span></span>'; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
	}
}, 99 );

/* Services archive: in menu order, all of them. */
add_action( 'pre_get_posts', function ( $q ) {
	if ( ! is_admin() && $q->is_main_query() && $q->is_post_type_archive( 'hf_service' ) ) {
		$q->set( 'orderby', 'menu_order' );
		$q->set( 'order', 'ASC' );
		$q->set( 'posts_per_page', -1 );
	}
} );

function hf_services( $exclude = 0 ) {
	return get_posts(
		array(
			'post_type'      => 'hf_service',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post__not_in'   => $exclude ? array( $exclude ) : array(),
		)
	);
}

function hf_reviews() {
	return get_posts(
		array(
			'post_type'      => 'hf_review',
			'posts_per_page' => 12,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
		)
	);
}
