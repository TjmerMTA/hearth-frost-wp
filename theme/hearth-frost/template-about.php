<?php
/**
 * Template Name: About
 */
get_header();
while ( have_posts() ) :
	the_post();
	get_template_part(
		'parts/page-hero',
		null,
		array(
			'title'   => 'A family business with <em>one focus</em>',
			'eyebrow' => 'About ' . esc_html( hf_mod( 'company' ) ),
			'image'   => has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'full' ) : hf_img( 'kitchen-dark-2.jpg', true ),
		)
	);
	?>
	<section class="section">
		<div class="wrap about-grid">
			<article class="entry entry-about">
				<?php the_content(); ?>
			</article>
			<div class="about-media">
				<img src="<?php echo hf_img( 'why_image' ); // phpcs:ignore ?>" alt="A technician at work" loading="lazy">
				<img src="<?php echo hf_img( 'knobs.jpg', true ); ?>" alt="Range control knobs" loading="lazy">
			</div>
		</div>
	</section>

	<section class="stats stats-dark" aria-label="By the numbers">
		<div class="wrap stats-in">
			<?php foreach ( hf_lines( 'stats' ) as $s ) : ?>
				<div class="stat reveal">
					<span class="stat-num"><?php echo esc_html( $s[0] ); ?></span>
					<span class="stat-label"><?php echo esc_html( $s[1] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="section">
		<div class="wrap">
			<div class="section-head">
				<p class="eyebrow">How we work</p>
				<h2 class="h-xl"><?php echo hf_heading( 'why_title' ); // phpcs:ignore ?></h2>
			</div>
			<ul class="pillars">
				<?php foreach ( hf_lines( 'why_points' ) as $i => $p ) : ?>
					<li class="reveal"><span class="step-num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><h3><?php echo esc_html( $p[0] ); ?></h3><p><?php echo esc_html( $p[1] ); ?></p></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>
	<?php
endwhile;
get_template_part( 'parts/cta' );
get_footer();
