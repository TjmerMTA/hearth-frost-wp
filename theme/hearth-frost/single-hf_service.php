<?php
/**
 * One service page.
 */
get_header();
while ( have_posts() ) :
	the_post();
	get_template_part(
		'parts/page-hero',
		null,
		array(
			'title'   => esc_html( get_the_title() ),
			'eyebrow' => '<a href="' . esc_url( get_post_type_archive_link( 'hf_service' ) ) . '">Services</a>',
			'text'    => get_the_excerpt(),
			'image'   => get_the_post_thumbnail_url( null, 'full' ),
		)
	);
	?>
	<section class="section">
		<div class="wrap article-grid">
			<article class="entry entry-service">
				<?php the_content(); ?>
			</article>
			<aside class="side">
				<div class="side-card">
					<p class="eyebrow">Book a technician</p>
					<h2 class="h-sm">Same-week appointments</h2>
					<p>Tell us the model and what is happening. We confirm a time by phone.</p>
					<a class="btn btn-dark btn-block" href="<?php echo esc_url( hf_schedule_url() ); ?>">Schedule service <?php echo hf_icon( 'arrow' ); // phpcs:ignore ?></a>
					<a class="side-phone" href="<?php echo esc_attr( hf_tel() ); ?>"><?php echo hf_icon( 'phone' ) . hf_text( 'phone' ); // phpcs:ignore ?></a>
					<p class="muted small"><?php echo hf_text( 'hours' ); // phpcs:ignore ?></p>
				</div>
				<div class="side-list">
					<p class="eyebrow">Other services</p>
					<ul>
						<?php foreach ( hf_services( get_the_ID() ) as $s ) : ?>
							<li><a href="<?php echo esc_url( get_permalink( $s ) ); ?>"><?php echo esc_html( get_the_title( $s ) ); ?><?php echo hf_icon( 'arrow' ); // phpcs:ignore ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</aside>
		</div>
	</section>
	<?php
endwhile;
get_template_part( 'parts/cta' );
get_footer();
