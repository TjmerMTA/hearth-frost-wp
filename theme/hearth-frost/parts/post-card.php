<article class="post-card reveal">
	<a class="post-media" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php
		if ( has_post_thumbnail() ) {
			the_post_thumbnail( 'large', array( 'loading' => 'lazy', 'alt' => '' ) );
		}
		?>
	</a>
	<p class="post-meta"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( max( 1, (int) round( str_word_count( wp_strip_all_tags( get_the_content() ) ) / 220 ) ) ); ?> min read</p>
	<h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<p class="post-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
</article>
