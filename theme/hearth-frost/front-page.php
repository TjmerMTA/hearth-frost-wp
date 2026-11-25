<?php
/**
 * Home page. Texts: Appearance → Customize → Home page.
 */
get_header();
$schedule = hf_schedule_url();
?>

<section class="hero" aria-label="Introduction">
	<img class="hero-img" src="<?php echo hf_img( 'hero_image' ); // phpcs:ignore ?>" alt="" fetchpriority="high">
	<div class="hero-shade"></div>
	<div class="wrap hero-in">
		<p class="eyebrow eyebrow-light reveal"><?php echo hf_text( 'hero_eyebrow' ); // phpcs:ignore ?></p>
		<h1 class="display reveal"><?php echo hf_heading( 'hero_title' ); // phpcs:ignore ?></h1>
		<p class="hero-text reveal"><?php echo hf_text( 'hero_text' ); // phpcs:ignore ?></p>
		<div class="hero-actions reveal">
			<a class="btn btn-light" href="<?php echo esc_url( $schedule ); ?>">Schedule service <?php echo hf_icon( 'arrow' ); // phpcs:ignore ?></a>
			<a class="btn btn-ghost-light" href="<?php echo esc_attr( hf_tel() ); ?>"><?php echo hf_icon( 'phone' ) . hf_text( 'phone' ); // phpcs:ignore ?></a>
		</div>
	</div>
	<div class="wrap hero-foot">
		<span><?php echo hf_text( 'hero_note' ); // phpcs:ignore ?></span>
		<a href="#intro" class="scroll-cue" aria-label="Scroll to the next section">↓</a>
	</div>
</section>

<section class="band-dark intro" id="intro">
	<div class="wrap">
		<div class="intro-head">
			<h2 class="h-xl reveal"><?php echo hf_heading( 'intro_title' ); // phpcs:ignore ?></h2>
			<p class="intro-text reveal"><?php echo hf_text( 'intro_text' ); // phpcs:ignore ?></p>
		</div>
		<div class="cats">
			<?php
			$archive = get_post_type_archive_link( 'hf_service' );
			for ( $i = 1; $i <= 3; $i++ ) :
				?>
				<a class="cat reveal" href="<?php echo esc_url( $archive ); ?>">
					<span class="cat-media"><img src="<?php echo hf_img( 'cat' . $i . '_image' ); // phpcs:ignore ?>" alt="" loading="lazy"></span>
					<span class="cat-title"><?php echo hf_text( 'cat' . $i . '_title' ); // phpcs:ignore ?></span>
					<span class="cat-text"><?php echo hf_text( 'cat' . $i . '_text' ); // phpcs:ignore ?></span>
				</a>
			<?php endfor; ?>
		</div>
	</div>
</section>

<section class="stats" aria-label="By the numbers">
	<div class="wrap stats-in">
		<?php foreach ( hf_lines( 'stats' ) as $s ) : ?>
			<div class="stat reveal">
				<span class="stat-num"><?php echo esc_html( $s[0] ); ?></span>
				<span class="stat-label"><?php echo esc_html( $s[1] ); ?></span>
			</div>
		<?php endforeach; ?>
	</div>
</section>

<section class="section services-home">
	<div class="wrap">
		<div class="section-head">
			<p class="eyebrow">Services</p>
			<h2 class="h-xl reveal"><?php echo hf_heading( 'services_title' ); // phpcs:ignore ?></h2>
			<p class="section-lead reveal"><?php echo hf_text( 'services_text' ); // phpcs:ignore ?></p>
		</div>
		<?php get_template_part( 'parts/service-grid', null, array( 'services' => hf_services() ) ); ?>
	</div>
</section>

<section class="section why">
	<div class="wrap">
		<div class="why-card">
			<div class="why-media reveal"><img src="<?php echo hf_img( 'why_image' ); // phpcs:ignore ?>" alt="A technician repairing an appliance component" loading="lazy"></div>
			<div class="why-body">
				<p class="eyebrow">Why <?php echo hf_text( 'company' ); // phpcs:ignore ?></p>
				<h2 class="h-lg reveal"><?php echo hf_heading( 'why_title' ); // phpcs:ignore ?></h2>
				<p class="reveal"><?php echo hf_text( 'why_text' ); // phpcs:ignore ?></p>
				<ul class="why-list">
					<?php foreach ( hf_lines( 'why_points' ) as $p ) : ?>
						<li class="reveal"><?php echo hf_icon( 'check' ); // phpcs:ignore ?><div><strong><?php echo esc_html( $p[0] ); ?></strong><span><?php echo esc_html( $p[1] ); ?></span></div></li>
					<?php endforeach; ?>
				</ul>
				<a class="btn btn-dark" href="<?php echo esc_url( hf_page_url( 'template-about.php', 'about' ) ); ?>">About us <?php echo hf_icon( 'arrow' ); // phpcs:ignore ?></a>
			</div>
		</div>
	</div>
</section>

<section class="section process">
	<div class="wrap">
		<div class="section-head">
			<p class="eyebrow">The process</p>
			<h2 class="h-xl reveal"><?php echo hf_heading( 'process_title' ); // phpcs:ignore ?></h2>
		</div>
		<ol class="steps">
			<?php foreach ( hf_lines( 'process' ) as $i => $s ) : ?>
				<li class="step reveal">
					<span class="step-num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
					<h3><?php echo esc_html( $s[0] ); ?></h3>
					<p><?php echo esc_html( $s[1] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<?php
$reviews = hf_reviews();
if ( $reviews ) :
	?>
<section class="reviews band-dark" aria-label="Reviews">
	<img class="reviews-bg" src="<?php echo hf_img( 'reviews_image' ); // phpcs:ignore ?>" alt="" loading="lazy">
	<div class="wrap reviews-in">
		<p class="eyebrow eyebrow-light">Testimonials</p>
		<h2 class="h-xl reveal"><?php echo hf_heading( 'reviews_title' ); // phpcs:ignore ?></h2>
		<div class="quotes" data-carousel>
			<?php foreach ( $reviews as $i => $r ) : ?>
				<figure class="quote<?php echo 0 === $i ? ' is-active' : ''; ?>" data-slide>
					<blockquote><?php echo esc_html( wp_strip_all_tags( $r->post_content ) ); ?></blockquote>
					<figcaption><strong><?php echo esc_html( $r->post_title ); ?></strong><span><?php echo esc_html( get_post_meta( $r->ID, '_hf_place', true ) ); ?></span></figcaption>
				</figure>
			<?php endforeach; ?>
			<div class="quote-nav">
				<button type="button" class="quote-btn" data-prev aria-label="Previous review">←</button>
				<span class="quote-count" data-count>1 / <?php echo count( $reviews ); ?></span>
				<button type="button" class="quote-btn" data-next aria-label="Next review">→</button>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="section areas-home">
	<div class="wrap areas-grid">
		<div class="areas-body">
			<p class="eyebrow">Service areas</p>
			<h2 class="h-lg reveal"><?php echo hf_heading( 'areas_title' ); // phpcs:ignore ?></h2>
			<p class="reveal"><?php echo hf_text( 'areas_text' ); // phpcs:ignore ?></p>
			<ul class="area-list">
				<?php foreach ( hf_lines( 'areas' ) as $a ) : ?>
					<li><?php echo hf_icon( 'pin' ) . esc_html( $a[0] ); // phpcs:ignore ?></li>
				<?php endforeach; ?>
			</ul>
			<a class="link-arrow" href="<?php echo esc_url( hf_page_url( 'template-areas.php', 'service-areas' ) ); ?>">All service areas <?php echo hf_icon( 'arrow' ); // phpcs:ignore ?></a>
		</div>
		<div class="areas-media reveal"><img src="<?php echo hf_img( 'areas_image' ); // phpcs:ignore ?>" alt="A marble kitchen with integrated appliances" loading="lazy"></div>
	</div>
</section>

<section class="section faq">
	<div class="wrap faq-grid">
		<div>
			<p class="eyebrow">FAQ</p>
			<h2 class="h-lg reveal"><?php echo hf_heading( 'faq_title' ); // phpcs:ignore ?></h2>
			<p class="muted">Something else on your mind? Call <a href="<?php echo esc_attr( hf_tel() ); ?>"><?php echo hf_text( 'phone' ); // phpcs:ignore ?></a>.</p>
		</div>
		<div class="accordion">
			<?php foreach ( hf_lines( 'faq' ) as $q ) : ?>
				<details>
					<summary><?php echo esc_html( $q[0] ); ?><?php echo hf_icon( 'plus' ); // phpcs:ignore ?></summary>
					<p><?php echo esc_html( $q[1] ); ?></p>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php get_template_part( 'parts/cta' ); ?>

<?php
$posts = get_posts( array( 'posts_per_page' => 3 ) );
if ( $posts ) :
	?>
<section class="section journal-home">
	<div class="wrap">
		<div class="section-head section-head-row">
			<div>
				<p class="eyebrow">Journal</p>
				<h2 class="h-lg">Care &amp; <em>maintenance</em></h2>
			</div>
			<a class="link-arrow" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>">All articles <?php echo hf_icon( 'arrow' ); // phpcs:ignore ?></a>
		</div>
		<div class="post-grid">
			<?php
			foreach ( $posts as $post ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
				setup_postdata( $post );
				get_template_part( 'parts/post-card' );
			endforeach;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php
get_footer();
