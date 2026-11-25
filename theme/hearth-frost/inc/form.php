<?php
/**
 * "Schedule service" form: stores the request under Requests in the admin and e-mails it.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_ajax_hf_request', 'hf_handle_request' );
add_action( 'wp_ajax_nopriv_hf_request', 'hf_handle_request' );

function hf_handle_request() {
	// phpcs:disable WordPress.Security.NonceVerification.Missing -- checked right below.
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'hf_request' ) ) {
		wp_send_json_error( array( 'message' => 'This page has expired. Please reload it and try again.' ), 403 );
	}
	if ( ! empty( $_POST['website'] ) ) { // Honeypot.
		wp_send_json_success( array( 'message' => hf_mod( 'form_ok' ) ) );
	}
	$get = function ( $k, $max = 200 ) {
		$v = isset( $_POST[ $k ] ) ? sanitize_text_field( wp_unslash( $_POST[ $k ] ) ) : '';
		return mb_substr( $v, 0, $max );
	};
	$name      = $get( 'name', 100 );
	$phone     = $get( 'phone', 40 );
	$email     = sanitize_email( $get( 'email', 150 ) );
	$zip       = $get( 'zip', 12 );
	$appliance = $get( 'appliance', 80 );
	$brand     = $get( 'brand', 40 );
	$model     = $get( 'model', 120 );
	$when      = $get( 'when', 80 );
	$msg       = isset( $_POST['msg'] ) ? mb_substr( sanitize_textarea_field( wp_unslash( $_POST['msg'] ) ), 0, 3000 ) : '';
	$page      = isset( $_POST['page'] ) ? esc_url_raw( wp_unslash( $_POST['page'] ) ) : '';
	$consent   = ! empty( $_POST['consent'] );
	// phpcs:enable

	$digits = preg_replace( '/\D/', '', $phone );
	if ( '' === $name || strlen( $digits ) < 10 || strlen( $digits ) > 15 ) {
		wp_send_json_error( array( 'message' => 'Please enter your name and a phone number we can reach you on.' ), 422 );
	}
	if ( ! $consent ) {
		wp_send_json_error( array( 'message' => 'Please agree to the Privacy Policy so we can contact you.' ), 422 );
	}

	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key = 'hf_rl_' . md5( $ip );
	$n   = (int) get_transient( $key );
	if ( $n >= 5 ) {
		wp_send_json_error( array( 'message' => 'Too many requests. Please try again later or call us.' ), 429 );
	}
	set_transient( $key, $n + 1, 10 * MINUTE_IN_SECONDS );

	$id = wp_insert_post(
		array(
			'post_type'    => 'hf_request',
			'post_status'  => 'pending',
			'post_title'   => $name . ' — ' . ( $appliance ? $appliance : 'service request' ),
			'post_content' => $msg,
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		wp_send_json_error( array( 'message' => 'We could not save your request. Please call us.' ), 500 );
	}
	foreach ( compact( 'name', 'phone', 'email', 'zip', 'appliance', 'brand', 'model', 'when', 'page' ) as $k => $v ) {
		update_post_meta( $id, '_hf_' . $k, $v );
	}

	$to = hf_mod( 'request_email' );
	if ( ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}
	$body  = "New service request\n\n";
	$body .= "Name: {$name}\nPhone: {$phone}\nE-mail: {$email}\nZIP: {$zip}\n";
	$body .= "Appliance: {$appliance}\nBrand: {$brand}\nModel / serial: {$model}\nPreferred time: {$when}\n\n";
	$body .= "Description:\n{$msg}\n\nAll requests: " . admin_url( 'edit.php?post_type=hf_request' );
	$headers = array();
	if ( $email ) {
		$headers[] = 'Reply-To: ' . $email;
	}
	wp_mail( $to, 'Service request: ' . $name, $body, $headers );

	wp_send_json_success( array( 'message' => hf_mod( 'form_ok' ) ) );
}

/** The form markup (used on the Schedule page). */
function hf_request_form() {
	$privacy = get_privacy_policy_url();
	?>
	<form class="hf-form" data-hf-form novalidate>
		<div class="form-grid">
			<label class="field"><span>Full name *</span><input type="text" name="name" autocomplete="name" required></label>
			<label class="field"><span>Phone *</span><input type="tel" name="phone" autocomplete="tel" required></label>
			<label class="field"><span>E-mail</span><input type="email" name="email" autocomplete="email"></label>
			<label class="field"><span>ZIP code</span><input type="text" name="zip" inputmode="numeric" autocomplete="postal-code"></label>
			<label class="field"><span>Appliance</span>
				<select name="appliance">
					<option value="">Select…</option>
					<option>Refrigerator / freezer</option>
					<option>Wine storage</option>
					<option>Ice maker</option>
					<option>Range / rangetop</option>
					<option>Cooktop</option>
					<option>Wall oven / steam oven</option>
					<option>Microwave / warming drawer</option>
					<option>Ventilation hood</option>
					<option>Dishwasher</option>
					<option>Outdoor grill / kitchen</option>
					<option>Maintenance visit</option>
				</select>
			</label>
			<label class="field"><span>Brand</span>
				<select name="brand">
					<option value="">Select…</option>
					<option>Sub-Zero</option>
					<option>Wolf</option>
					<option>Cove</option>
					<option>Not sure</option>
				</select>
			</label>
			<label class="field"><span>Model / serial number</span><input type="text" name="model" placeholder="Optional — found inside the door"></label>
			<label class="field"><span>Preferred time</span>
				<select name="when">
					<option>As soon as possible</option>
					<option>Morning (8 am – 12 pm)</option>
					<option>Afternoon (12 pm – 5 pm)</option>
					<option>Next week</option>
				</select>
			</label>
			<label class="field field-wide"><span>What is happening?</span><textarea name="msg" rows="4" placeholder="e.g. The refrigerator is not cooling and the display shows an error."></textarea></label>
		</div>
		<input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
		<label class="consent"><input type="checkbox" name="consent" value="1" required>
			<span>I agree to be contacted about my request and accept the <a href="<?php echo esc_url( $privacy ? $privacy : home_url( '/privacy-policy/' ) ); ?>">Privacy Policy</a>.</span>
		</label>
		<div class="form-foot">
			<button type="submit" class="btn btn-dark">Request appointment <?php echo hf_icon( 'arrow' ); // phpcs:ignore ?></button>
			<p class="form-msg" role="status" aria-live="polite"></p>
		</div>
	</form>
	<?php
}
