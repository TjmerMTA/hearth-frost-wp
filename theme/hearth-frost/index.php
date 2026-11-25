<?php
/**
 * Journal (posts page), archives and search results.
 */
get_header();
?>
<section class="page-head">
	<div class="wrap">
		<p class="eyebrow">Journal</p>
		<h1 class="display display-sm display-ink">
			<?php
			if ( is_home() ) {
				echo 'Care &amp; <em>maintenance</em>';
			} elseif ( is_search() ) {
				echo 'Search: ' . esc_html( get_search_query() );
			} else {
				the_archive_title();
			}
			?>
		</h1>
		<?php if ( is_home() ) : ?>
			<p class="section-lead">Practical advice from our technicians on keeping luxury appliances at their best.</p>
		<?php endif; ?>
	</div>
</section>
<section class="section section-flush">
	<div class="wrap">
		<?php if ( have_posts() ) : ?>
			<div class="post-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					get_template_part( 'parts/post-card' );
				endwhile;
				?>
			</div>
			<div class="pager"><?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?></div>
		<?php else : ?>
			<p>Nothing here yet.</p>
		<?php endif; ?>
	</div>
</section>
<?php
get_template_part( 'parts/cta' );
get_footer();
