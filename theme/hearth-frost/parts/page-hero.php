<?php
/**
 * Full-bleed photo header for inner pages. Args: title, eyebrow, text, image (URL).
 */
$a = wp_parse_args(
	$args,
	array(
		'title'   => get_the_title(),
		'eyebrow' => '',
		'text'    => '',
		'image'   => '',
	)
);
?>
<section class="page-hero">
	<?php if ( $a['image'] ) : ?>
		<img class="hero-img" src="<?php echo esc_url( $a['image'] ); ?>" alt="" fetchpriority="high">
	<?php endif; ?>
	<div class="hero-shade"></div>
	<div class="wrap page-hero-in">
		<?php if ( $a['eyebrow'] ) : ?>
			<p class="eyebrow eyebrow-light"><?php echo wp_kses_post( $a['eyebrow'] ); ?></p>
		<?php endif; ?>
		<h1 class="display display-sm"><?php echo wp_kses_post( $a['title'] ); ?></h1>
		<?php if ( $a['text'] ) : ?>
			<p class="hero-text"><?php echo esc_html( $a['text'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
