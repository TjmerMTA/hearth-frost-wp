<?php
/**
 * Appearance → Customize: "Company & contacts" and "Home page".
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hf_customizer_fields() {
	$tip_em    = 'Wrap a word in *asterisks* to set it in italics.';
	$tip_lines = 'One per line: Title|Text';
	return array(
		'hf_company' => array(
			'title'  => 'Company & contacts',
			'fields' => array(
				'company'       => array( 'Short name (logo)', 'text' ),
				'company_long'  => array( 'Full legal / trading name', 'text' ),
				'tagline'       => array( 'Line under the logo', 'text' ),
				'phone'         => array( 'Phone', 'text' ),
				'email'         => array( 'E-mail', 'text' ),
				'address'       => array( 'Address', 'text' ),
				'hours'         => array( 'Hours', 'text' ),
				'region'        => array( 'Region served', 'text' ),
				'topbar'        => array( 'Top bar text', 'text' ),
				'request_email' => array( 'Send service requests to (e-mail)', 'text', 'Empty = the site admin e-mail.' ),
				'form_ok'       => array( 'Message after a request is sent', 'textarea' ),
				'trademark'     => array( 'Trademark notice (footer)', 'textarea' ),
			),
		),
		'hf_home'    => array(
			'title'  => 'Home page',
			'fields' => array(
				'hero_eyebrow'   => array( 'Hero — small line above', 'text' ),
				'hero_title'     => array( 'Hero — title', 'text', $tip_em ),
				'hero_text'      => array( 'Hero — text', 'textarea' ),
				'hero_note'      => array( 'Hero — note at the bottom', 'text' ),
				'hero_image'     => array( 'Hero — photo', 'image' ),
				'intro_title'    => array( 'Intro — title', 'text', $tip_em ),
				'intro_text'     => array( 'Intro — text', 'textarea' ),
				'cat1_title'     => array( 'Category 1 — title', 'text' ),
				'cat1_text'      => array( 'Category 1 — text', 'text' ),
				'cat1_image'     => array( 'Category 1 — photo', 'image' ),
				'cat2_title'     => array( 'Category 2 — title', 'text' ),
				'cat2_text'      => array( 'Category 2 — text', 'text' ),
				'cat2_image'     => array( 'Category 2 — photo', 'image' ),
				'cat3_title'     => array( 'Category 3 — title', 'text' ),
				'cat3_text'      => array( 'Category 3 — text', 'text' ),
				'cat3_image'     => array( 'Category 3 — photo', 'image' ),
				'stats'          => array( 'Numbers', 'textarea', 'One per line: Number|Label' ),
				'services_title' => array( 'Services — title', 'text', $tip_em ),
				'services_text'  => array( 'Services — text', 'textarea', 'The services themselves are edited under Services in the admin menu.' ),
				'why_title'      => array( 'Why us — title', 'text', $tip_em ),
				'why_text'       => array( 'Why us — text', 'textarea' ),
				'why_points'     => array( 'Why us — points', 'textarea', $tip_lines ),
				'why_image'      => array( 'Why us — photo', 'image' ),
				'process_title'  => array( 'Process — title', 'text', $tip_em ),
				'process'        => array( 'Process — steps', 'textarea', $tip_lines ),
				'reviews_title'  => array( 'Reviews — title', 'text', 'Reviews themselves are edited under Reviews in the admin menu.' ),
				'reviews_image'  => array( 'Reviews — background photo', 'image' ),
				'areas_title'    => array( 'Areas — title', 'text', $tip_em ),
				'areas_text'     => array( 'Areas — text', 'textarea' ),
				'areas'          => array( 'Areas — list', 'textarea', 'One area per line.' ),
				'areas_image'    => array( 'Areas — photo', 'image' ),
				'faq_title'      => array( 'FAQ — title', 'text', $tip_em ),
				'faq'            => array( 'FAQ — questions', 'textarea', 'One per line: Question|Answer' ),
				'cta_title'      => array( 'Final call — title', 'text', $tip_em ),
				'cta_text'       => array( 'Final call — text', 'textarea' ),
				'cta_image'      => array( 'Final call — photo', 'image' ),
			),
		),
	);
}

add_action( 'customize_register', function ( $wp ) {
	$d        = hf_defaults();
	$priority = 25;
	foreach ( hf_customizer_fields() as $section => $cfg ) {
		$wp->add_section( $section, array( 'title' => $cfg['title'], 'priority' => $priority++ ) );
		foreach ( $cfg['fields'] as $key => $f ) {
			$id   = 'hf_' . $key;
			$type = $f[1];
			$wp->add_setting(
				$id,
				array(
					'default'           => isset( $d[ $key ] ) ? $d[ $key ] : '',
					'sanitize_callback' => 'textarea' === $type ? 'sanitize_textarea_field' : ( 'image' === $type ? 'hf_sanitize_image' : 'sanitize_text_field' ),
				)
			);
			$args = array(
				'label'       => $f[0],
				'section'     => $section,
				'description' => isset( $f[2] ) ? $f[2] : '',
			);
			if ( 'image' === $type ) {
				$wp->add_control( new WP_Customize_Image_Control( $wp, $id, $args ) );
			} else {
				$args['type'] = $type;
				$wp->add_control( $id, $args );
			}
		}
	}
} );

/** Images: a URL from the Media Library or a bundled file name. */
function hf_sanitize_image( $v ) {
	$v = trim( (string) $v );
	if ( preg_match( '/^[\w.-]+\.(jpe?g|png|webp|avif)$/i', $v ) ) {
		return $v;
	}
	return esc_url_raw( $v );
}
