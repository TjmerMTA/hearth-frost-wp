<?php
/**
 * Template Name: Schedule service (form + contacts)
 */
get_header();
?>
<section class="section schedule">
	<div class="wrap schedule-grid">
		<div class="schedule-intro">
			<p class="eyebrow">Schedule service</p>
			<h1 class="display display-sm display-ink">Request an <em>appointment</em></h1>
			<p class="section-lead">Fill in the form and we will call you to confirm a time — usually within one business hour. Prefer to talk? We are glad to help by phone.</p>
			<ul class="contact-list">
				<li><?php echo hf_icon( 'phone' ); // phpcs:ignore ?><div><span>Phone</span><a href="<?php echo esc_attr( hf_tel() ); ?>"><?php echo hf_text( 'phone' ); // phpcs:ignore ?></a></div></li>
				<li><?php echo hf_icon( 'mail' ); // phpcs:ignore ?><div><span>E-mail</span><a href="mailto:<?php echo esc_attr( hf_fill( hf_mod( 'email' ) ) ); ?>"><?php echo hf_text( 'email' ); // phpcs:ignore ?></a></div></li>
				<li><?php echo hf_icon( 'clock' ); // phpcs:ignore ?><div><span>Hours</span><?php echo hf_text( 'hours' ); // phpcs:ignore ?></div></li>
				<li><?php echo hf_icon( 'pin' ); // phpcs:ignore ?><div><span>Office</span><?php echo hf_text( 'address' ); // phpcs:ignore ?></div></li>
			</ul>
			<?php
			while ( have_posts() ) :
				the_post();
				if ( trim( get_the_content() ) ) {
					echo '<div class="entry">';
					the_content();
					echo '</div>';
				}
			endwhile;
			?>
		</div>
		<div class="schedule-form">
			<?php hf_request_form(); ?>
		</div>
	</div>
</section>
<?php
get_footer();
