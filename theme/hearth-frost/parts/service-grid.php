<?php
/**
 * Grid of service cards. Args: services (WP_Post[]).
 */
$services = isset( $args['services'] ) ? $args['services'] : array();
if ( ! $services ) {
	return;
}
?>
<div class="service-grid">
	<?php foreach ( $services as $i => $s ) : ?>
		<a class="service-card reveal" href="<?php echo esc_url( get_permalink( $s ) ); ?>">
			<span class="service-media">
				<?php
				if ( has_post_thumbnail( $s ) ) {
					echo get_the_post_thumbnail( $s, 'large', array( 'loading' => 'lazy', 'alt' => '' ) );
				}
				?>
			</span>
			<span class="service-body">
				<span class="service-num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
				<span class="service-title"><?php echo esc_html( get_the_title( $s ) ); ?></span>
				<span class="service-text"><?php echo esc_html( get_the_excerpt( $s ) ); ?></span>
				<span class="link-arrow">Learn more <?php echo hf_icon( 'arrow' ); // phpcs:ignore ?></span>
			</span>
		</a>
	<?php endforeach; ?>
</div>
