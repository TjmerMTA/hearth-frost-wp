<?php
get_header();
?>
<section class="page-head page-404">
	<div class="wrap">
		<p class="eyebrow">Error 404</p>
		<h1 class="display display-sm display-ink">This page has <em>moved on</em></h1>
		<p class="section-lead">The address may be out of date. Here are a few good places to continue.</p>
		<div class="hero-actions">
			<a class="btn btn-dark" href="<?php echo esc_url( home_url( '/' ) ); ?>">Home page</a>
			<a class="btn btn-ghost" href="<?php echo esc_url( get_post_type_archive_link( 'hf_service' ) ); ?>">Our services</a>
		</div>
	</div>
</section>
<?php
get_footer();
