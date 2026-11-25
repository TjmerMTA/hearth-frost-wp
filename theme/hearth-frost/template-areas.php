<?php
/**
 * Template Name: Service areas
 */
get_header();
while ( have_posts() ) :
	the_post();
	get_template_part(
		'parts/page-hero',
		null,
		array(
			'title'   => hf_heading( 'areas_title' ),
			'eyebrow' => 'Service areas',
			'text'    => hf_fill( hf_mod( 'areas_text' ) ),
			'image'   => has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'full' ) : hf_img( 'areas_image' ),
		)
	);
	?>
	<section class="section">
		<div class="wrap">
			<ul class="area-cards">
				<?php foreach ( hf_lines( 'areas' ) as $i => $a ) : ?>
					<li class="area-card">
						<span class="step-num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<h2 class="h-sm"><?php echo esc_html( $a[0] ); ?></h2>
						<p>Weekday appointments · Repair, maintenance &amp; warranty service</p>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php if ( trim( get_the_content() ) ) : ?>
				<div class="entry entry-narrow"><?php the_content(); ?></div>
			<?php endif; ?>
			<div class="note-card">
				<div>
					<h2 class="h-sm">Not on the list?</h2>
					<p>We regularly travel beyond these areas for maintenance visits and complex repairs. Call and we will tell you right away.</p>
				</div>
				<a class="btn btn-dark" href="<?php echo esc_attr( hf_tel() ); ?>"><?php echo hf_icon( 'phone' ) . hf_text( 'phone' ); // phpcs:ignore ?></a>
			</div>
		</div>
	</section>
	<?php
endwhile;
get_template_part( 'parts/cta' );
get_footer();
