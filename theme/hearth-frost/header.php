<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#0f0f0e">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header" data-header>
	<div class="topbar">
		<div class="wrap topbar-in">
			<span class="topbar-note"><?php echo hf_text( 'topbar' ); // phpcs:ignore ?></span>
			<span class="topbar-meta">
				<span><?php echo hf_icon( 'clock' ) . hf_text( 'hours' ); // phpcs:ignore ?></span>
				<a href="<?php echo esc_attr( hf_tel() ); ?>"><?php echo hf_icon( 'phone' ) . hf_text( 'phone' ); // phpcs:ignore ?></a>
			</span>
		</div>
	</div>
	<div class="wrap header-in">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( hf_mod( 'company' ) ); ?> — home">
			<?php if ( has_custom_logo() ) : ?>
				<?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full', false, array( 'class' => 'brand-logo' ) ); ?>
			<?php else : ?>
				<span class="brand-name"><?php echo hf_text( 'company' ); // phpcs:ignore ?></span>
				<span class="brand-tag"><?php echo hf_text( 'tagline' ); // phpcs:ignore ?></span>
			<?php endif; ?>
		</a>

		<nav class="nav" aria-label="Main">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'menu',
					'depth'          => 2,
					'fallback_cb'    => 'hf_fallback_menu',
				)
			);
			?>
		</nav>

		<div class="header-actions">
			<a class="header-phone" href="<?php echo esc_attr( hf_tel() ); ?>" aria-label="Call <?php echo esc_attr( hf_mod( 'phone' ) ); ?>"><?php echo hf_icon( 'phone' ); // phpcs:ignore ?></a>
			<a class="btn btn-pill btn-header" href="<?php echo esc_url( hf_schedule_url() ); ?>">Schedule service</a>
			<button class="burger" type="button" aria-expanded="false" aria-controls="mobile-menu" data-burger>
				<span class="burger-open"><?php echo hf_icon( 'menu' ); // phpcs:ignore ?></span>
				<span class="burger-close"><?php echo hf_icon( 'close' ); // phpcs:ignore ?></span>
				<span class="sr-only">Menu</span>
			</button>
		</div>
	</div>
</header>

<div class="mobile-menu" id="mobile-menu" data-mobile-menu hidden>
	<div class="wrap">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'mobile-list',
				'depth'          => 2,
				'fallback_cb'    => 'hf_fallback_menu',
			)
		);
		?>
		<div class="mobile-contacts">
			<a class="btn btn-light" href="<?php echo esc_url( hf_schedule_url() ); ?>">Schedule service <?php echo hf_icon( 'arrow' ); // phpcs:ignore ?></a>
			<a class="mobile-phone" href="<?php echo esc_attr( hf_tel() ); ?>"><?php echo hf_text( 'phone' ); // phpcs:ignore ?></a>
			<span><?php echo hf_text( 'hours' ); // phpcs:ignore ?></span>
		</div>
	</div>
</div>

<main id="main" class="site-main">
