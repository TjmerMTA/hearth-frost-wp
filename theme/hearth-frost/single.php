<?php
/**
 * Journal article.
 */
get_header();
while ( have_posts() ) :
	the_post();
	?>
	<section class="page-head">
		<div class="wrap entry-narrow">
			<p class="eyebrow"><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>">Journal</a> · <?php echo esc_html( get_the_date() ); ?></p>
			<h1 class="display display-sm display-ink"><?php the_title(); ?></h1>
		</div>
	</section>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="wrap"><figure class="article-cover"><?php the_post_thumbnail( 'full', array( 'alt' => '' ) ); ?></figure></div>
	<?php endif; ?>
	<section class="section section-flush">
		<div class="wrap">
			<article class="entry entry-narrow"><?php the_content(); ?></article>
			<div class="entry-narrow note-card note-card-sm">
				<div>
					<h2 class="h-sm">Need a technician?</h2>
					<p>If something still is not right, we will diagnose it properly.</p>
				</div>
				<a class="btn btn-dark" href="<?php echo esc_url( hf_schedule_url() ); ?>">Schedule service <?php echo hf_icon( 'arrow' ); // phpcs:ignore ?></a>
			</div>
		</div>
	</section>
	<?php
endwhile;
get_footer();
