</main>

<footer class="site-footer">
	<div class="wrap">
		<div class="footer-top">
			<div class="footer-brand">
				<a class="brand brand-footer" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<span class="brand-name"><?php echo hf_text( 'company' ); // phpcs:ignore ?></span>
					<span class="brand-tag"><?php echo hf_text( 'tagline' ); // phpcs:ignore ?></span>
				</a>
				<p class="footer-lead">Repair, maintenance and warranty service for Sub-Zero, Wolf and Cove appliances across <?php echo hf_text( 'region' ); // phpcs:ignore ?>.</p>
				<a class="btn btn-light" href="<?php echo esc_url( hf_schedule_url() ); ?>">Schedule service <?php echo hf_icon( 'arrow' ); // phpcs:ignore ?></a>
			</div>

			<div class="footer-col">
				<h2 class="footer-h">Services</h2>
				<ul>
					<?php foreach ( hf_services() as $s ) : ?>
						<li><a href="<?php echo esc_url( get_permalink( $s ) ); ?>"><?php echo esc_html( get_the_title( $s ) ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>

			<div class="footer-col">
				<h2 class="footer-h">Company</h2>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => 'hf_fallback_menu',
					)
				);
				?>
			</div>

			<div class="footer-col footer-contact">
				<h2 class="footer-h">Customer care</h2>
				<p><a class="footer-phone" href="<?php echo esc_attr( hf_tel() ); ?>"><?php echo hf_text( 'phone' ); // phpcs:ignore ?></a></p>
				<p><a href="mailto:<?php echo esc_attr( hf_fill( hf_mod( 'email' ) ) ); ?>"><?php echo hf_text( 'email' ); // phpcs:ignore ?></a></p>
				<p><?php echo hf_text( 'hours' ); // phpcs:ignore ?></p>
				<p class="muted"><?php echo hf_text( 'address' ); // phpcs:ignore ?></p>
			</div>
		</div>

		<div class="footer-bottom">
			<p class="trademark"><?php echo hf_text( 'trademark' ); // phpcs:ignore ?></p>
			<div class="footer-legal">
				<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo hf_text( 'company_long' ); // phpcs:ignore ?></span>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'legal',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => '__return_false',
					)
				);
				?>
				<a href="#top" class="to-top" data-to-top>Back to top ↑</a>
			</div>
		</div>
	</div>
</footer>

<a class="mobile-call" href="<?php echo esc_attr( hf_tel() ); ?>"><?php echo hf_icon( 'phone' ); // phpcs:ignore ?> Call</a>
<a class="mobile-book" href="<?php echo esc_url( hf_schedule_url() ); ?>">Schedule service</a>

<?php wp_footer(); ?>
</body>
</html>
