<?php
/**
 * Services overview (/services/).
 */
get_header();
get_template_part(
	'parts/page-hero',
	null,
	array(
		'title'   => 'Our <em>services</em>',
		'eyebrow' => 'Sub-Zero · Wolf · Cove',
		'text'    => hf_fill( hf_mod( 'services_text' ) ),
		'image'   => hf_img( 'range-detail.jpg', true ),
	)
);
?>
<section class="section">
	<div class="wrap">
		<?php get_template_part( 'parts/service-grid', null, array( 'services' => hf_services() ) ); ?>
	</div>
</section>

<section class="section process process-tight">
	<div class="wrap">
		<div class="section-head">
			<p class="eyebrow">The process</p>
			<h2 class="h-xl"><?php echo hf_heading( 'process_title' ); // phpcs:ignore ?></h2>
		</div>
		<ol class="steps">
			<?php foreach ( hf_lines( 'process' ) as $i => $s ) : ?>
				<li class="step reveal">
					<span class="step-num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					<h3><?php echo esc_html( $s[0] ); ?></h3>
					<p><?php echo esc_html( $s[1] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
<?php
get_template_part( 'parts/cta' );
get_footer();
