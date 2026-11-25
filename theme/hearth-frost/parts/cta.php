<section class="cta band-dark">
	<img class="cta-bg" src="<?php echo hf_img( 'cta_image' ); // phpcs:ignore ?>" alt="" loading="lazy">
	<div class="wrap cta-in">
		<h2 class="h-xl reveal"><?php echo hf_heading( 'cta_title' ); // phpcs:ignore ?></h2>
		<p class="reveal"><?php echo hf_text( 'cta_text' ); // phpcs:ignore ?></p>
		<div class="hero-actions reveal">
			<a class="btn btn-light" href="<?php echo esc_url( hf_schedule_url() ); ?>">Schedule service <?php echo hf_icon( 'arrow' ); // phpcs:ignore ?></a>
			<a class="btn btn-ghost-light" href="<?php echo esc_attr( hf_tel() ); ?>"><?php echo hf_icon( 'phone' ) . hf_text( 'phone' ); // phpcs:ignore ?></a>
		</div>
	</div>
</section>
