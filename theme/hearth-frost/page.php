<?php
/**
 * Default page. With a featured image: photo header; without one (e.g. legal pages): a calm reading layout
 * with an automatic table of contents built from the page's headings.
 */
get_header();
while ( have_posts() ) :
	the_post();
	if ( has_post_thumbnail() ) :
		get_template_part( 'parts/page-hero', null, array( 'title' => esc_html( get_the_title() ), 'image' => get_the_post_thumbnail_url( null, 'full' ) ) );
		?>
		<section class="section"><div class="wrap"><div class="entry entry-narrow"><?php the_content(); ?></div></div></section>
		<?php
	else :
		$is_legal = (int) get_option( 'wp_page_for_privacy_policy' ) === get_the_ID() || 'terms-of-use' === get_post_field( 'post_name' );
		?>
		<section class="page-head">
			<div class="wrap">
				<p class="eyebrow"><?php echo $is_legal ? 'Legal' : esc_html( hf_mod( 'company' ) ); ?></p>
				<h1 class="display display-sm display-ink"><?php the_title(); ?></h1>
				<?php if ( $is_legal ) : ?>
					<p class="muted">Last updated <?php echo esc_html( get_the_modified_date( 'F j, Y' ) ); ?></p>
				<?php endif; ?>
			</div>
		</section>
		<section class="section section-flush">
			<div class="wrap<?php echo $is_legal ? ' legal-grid' : ''; ?>">
				<?php if ( $is_legal ) : ?>
					<nav class="toc" aria-label="On this page" data-toc><p class="eyebrow">On this page</p><ol></ol></nav>
				<?php endif; ?>
				<article class="entry entry-narrow<?php echo $is_legal ? ' entry-legal' : ''; ?>" data-toc-source><?php the_content(); ?></article>
			</div>
		</section>
		<?php
	endif;
endwhile;
get_footer();
