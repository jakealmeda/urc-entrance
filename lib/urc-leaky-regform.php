<?php

add_shortcode('urc_leaky_paywall_register_form', 'do_leaky_paywall_register_form_urc');
function do_leaky_paywall_register_form_urc($atts)
{

	$a = shortcode_atts(array(
		'level_id' => '',
	), $atts);

	$settings = get_leaky_paywall_settings();

	if (is_numeric($a['level_id'])) {
		$level_id = $a['level_id'];
	} else {
		$level_id = isset($_GET['level_id']) ? $_GET['level_id'] : null;
	}

	$level = get_leaky_paywall_subscription_level($level_id);

	if (is_null($level_id) || !$level || is_level_deleted($level_id)) {
		$content = '<p>' . __('Please', 'leaky-paywall') . ' <a href="' . get_page_link($settings['page_for_subscription']) . '">' . __('go to the subscribe page', 'leaky-paywall') . '</a> ' . __('to choose a subscription level.', 'leaky-paywall') . '</p>';
		return $content;
	}

	global $blog_id;
	if (is_multisite_premium()) {
		$site = '_' . $blog_id;
	} else {
		$site = '';
	}

	$currency = leaky_paywall_get_currency();
	$currencies = leaky_paywall_supported_currencies();
	$publishable_key = 'on' === $settings['test_mode'] ? $settings['test_publishable_key'] : $settings['live_publishable_key'];

	$userdata = get_userdata(get_current_user_id());
	if (!empty($userdata)) {
		$email = $userdata->user_email;
		$username = $userdata->user_login;
		$first = $userdata->first_name;
		$last = $userdata->last_name;
	} else {
		$email = leaky_paywall_old_form_value('email_address', false);
		$username = leaky_paywall_old_form_value('username', false);
		$first = leaky_paywall_old_form_value('first_name', false);
		$last = leaky_paywall_old_form_value('last_name', false);
	}
	ob_start();

	// show any error messages after form submission
	leaky_paywall_show_error_messages('register');
?>
<?php /*
	<div class="leaky-paywall-subscription-details-wrapper">

		<h3 class="leaky-paywall-subscription-details-title"><?php printf(__('Order Summary', 'leaky-paywall')); ?></h3>

		<ul class="leaky-paywall-subscription-details">
			<li class="leaky-paywall-subscription-details-subscription-name"><strong><?php printf(__('Your Order:', 'leaky-paywall')); ?></strong> <?php echo apply_filters('leaky_paywall_registration_level_name', $level['label']); ?></li>
			<li class="leaky-paywall-subscription-details-subscription-length"><strong><?php printf(__('Subscription Length:', 'leaky-paywall')); ?></strong> <?php echo $level['subscription_length_type'] == 'unlimited' ? __('Forever', 'leaky-paywall') : $level['interval_count'] . ' ' . $level['interval'] . ($level['interval_count'] > 1  ? 's' : ''); ?></li>
			<li class="leaky-paywall-subscription-details-recurring"><strong><?php printf(__('Recurring:', 'leaky-paywall')); ?> </strong> <?php echo !empty($level['recurring']) && $level['recurring'] == 'on' ? __('Yes', 'leaky-paywall') : __('No', 'leaky-paywall'); ?></li>
			<li class="leaky-paywall-subscription-details-content-access"><strong><?php printf(__('Content Access:', 'leaky-paywall')); ?></strong>

				<?php
				$content_access_description = '';
				$i = 0;

				if (isset($level['post_types']) && !empty($level['post_types'] && !$level['registration_form_description'])) {
					foreach ($level['post_types'] as $type) {
						if ($i > 0) {
							$content_access_description .= ', ';
						}

						$post_type = get_post_type_object($type['post_type']);

						if ($type['allowed'] == 'unlimited') {
							$content_access_description .= ucfirst($type['allowed']) . ' ' . $post_type->labels->name;
						} else {
							$post_type_label = $type['allowed_value'] === '1' ? $post_type->labels->singular_name : $post_type->labels->name;
							$content_access_description .= $type['allowed_value'] . ' ' . $post_type_label;
						}

						$i++;
					}
				} else {
					$content_access_description = stripslashes($level['registration_form_description']);
				}


				echo apply_filters('leaky_paywall_content_access_description', $content_access_description, $level, $level_id);
				?>

			</li>

		</ul>

		<p class="leaky-paywall-subscription-total">

			<?php $display_price = leaky_paywall_get_level_display_price($level); ?>

			<strong><?php printf(__('Total:', 'leaky-paywall')); ?></strong> <?php echo apply_filters('leaky_paywall_your_subscription_total', $display_price, $level); ?>
		</p>

	</div>

	<?php do_action('leaky_paywall_before_registration_form', $level); ?>

	<?php
	if ($level['price'] > 0) {
	?>
		<div class="leaky-paywall-form-steps">
			<div class="leaky-paywall-form-account-setup-step leaky-paywall-form-step active">
				<span class="step-number">1</span>
				<span class="step-title"><?php _e('Account Setup', 'leaky-paywall'); ?></span>
			</div>
			<div class="leaky-paywall-form-payment-setup-step leaky-paywall-form-step">
				<span class="step-number">2</span>
				<span class="step-title"><?php _e('Payment', 'leaky-paywall'); ?></span>
			</div>
		</div>
	<?php
	}
	?>
*/ ?>


	<form action="" method="POST" name="payment-form" id="leaky-paywall-payment-form" class="leaky-paywall-payment-form">
		<span class="payment-errors"></span>

		<div id="leaky-paywall-registration-errors"></div>

		<div class="leaky-paywall-registration-user-container">

			<?php do_action('leaky_paywall_before_registration_form_user_fields', $level); ?>

			<div class="leaky-paywall-user-fields">
<?php /*
				<h3><?php printf(__('Your Details', 'leaky-paywall')); ?></h3>
*/ ?>
				<p class="form-row first-name">
					<label for="first_name"><?php printf(__('First Name', 'leaky-paywall')); ?> <i class="required">*</i></label>
					<input type="text" size="20" name="first_name" required value="<?php echo $first; ?>" />
				</p>

				<p class="form-row last-name">
					<label for="last_name"><?php printf(__('Last Name', 'leaky-paywall')); ?> <i class="required">*</i></label>
					<input type="text" size="20" name="last_name" required value="<?php echo $last; ?>" />
				</p>

				<p class="form-row email-address">
					<label for="email_address"><?php printf(__('Email Address', 'leaky-paywall')); ?> <i class="required">*</i></label>
					<input type="email" size="20" id="email_address" name="email_address" required value="<?php echo $email; ?>" <?php echo !empty($email) && !empty($userdata) ? 'disabled="disabled"' : ''; ?> />
				</p>

			</div>

			<?php do_action('leaky_paywall_before_registration_form_account_fields', $level); ?>

			<div class="leaky-paywall-account-fields">
<?php /*
				<h3><?php printf(__('Account Details', 'leaky-paywall')); ?></h3>
*/ ?>
				<?php
				if ($settings['remove_username_field'] == 'off') {
				?>
					<p class="form-row username">
						<label for="username"><?php printf(__('Username', 'leaky-paywall')); ?> <i class="required">*</i></label>
						<input type="text" size="20" name="username" id="username" required value="<?php echo $username; ?>" <?php echo !empty($username) && !empty($userdata) ? 'disabled="disabled"' : ''; ?> />
					</p>
				<?php
				}
				?>



				<?php if (!is_user_logged_in()) { ?>

					<p class="form-row password">
						<label for="password"><?php printf(__('Password', 'leaky-paywall')); ?> <i class="required">*</i></label>
						<input type="password" size="20" id="password" required name="password" />
					</p>

					<p class="form-row confirm-password">
						<label for="confirm_password"><?php printf(__('Confirm Password', 'leaky-paywall')); ?> <i class="required">*</i></label>
						<input type="password" size="20" id="confirm_password" required name="confirm_password" />
					</p>

				<?php } ?>

			</div>

			<?php do_action('leaky_paywall_after_password_registration_field', $level_id, $level); ?>

			<?php if ($level['price'] != 0) {
			?>
				<p>
					<button id="leaky-paywall-registration-next" type="button"><?php _e('Next', 'leaky-paywall'); ?></button>
				</p>
			<?php
			} ?>


		</div> <!-- leaky-paywall-registration-user-container -->

		<div class="leaky-paywall-registration-payment-container">

			<?php

			$gateways = leaky_paywall_get_enabled_payment_gateways($level_id);
add_shortcode('urc_leaky_paywall_register_form', 'do_leaky_paywall_register_form_urc');

			if ($gateways && $level['price'] != 0) {

				foreach ($gateways as $key => $gateway) {

					echo '<input type="hidden" name="gateway" value="' . esc_attr($key) . '" />';
				}
			} else {
				echo '<input type="hidden" name="gateway" value="free_registration" />';
			}

			?>

			<?php
			if ($level['price'] > 0) {
				$total_price = str_replace(',', '', number_format($level['price'], 2));
			} else {
				$total_price = 0;
			}

			if ($total_price > 0) { ?>
				<h3><?php printf(__('Payment Information', 'leaky-paywall')); ?></h3>
			<?php } ?>

			<?php if (leaky_paywall_get_current_mode() == 'test') {
			?>
				<div class="leaky-paywall-test-mode-wrapper">
					<p class="leaky-paywall-test-mode-text">The site is currently in test mode.</p>
				<?php
			} ?>

				<?php do_action('leaky_paywall_before_registration_submit_field', $gateways, $level_id); ?>

				<?php if (leaky_paywall_get_current_mode() == 'test') {
				?>
				</div>
			<?php } ?>

			<div class="leaky-paywall-checkout-button">
				<button id="leaky-paywall-submit" type="submit"><?php echo leaky_paywall_get_registration_checkout_button_text(); ?></button>
			</div>


		</div> <!-- .leaky-paywall-registration-payment-container -->

		<input type="hidden" name="level_price" value="<?php echo $total_price; ?>" />
		<input type="hidden" name="currency" value="<?php echo $currency; ?>" />
		<input type="hidden" name="description" value="<?php echo $level['label']; ?>" />
		<input type="hidden" name="level_id" id="level-id" value="<?php echo $level_id; ?>" />
		<input type="hidden" name="interval" value="<?php echo $level['interval']; ?>" />
		<input type="hidden" name="interval_count" value="<?php echo $level['interval_count']; ?>" />
		<input type="hidden" name="recurring" value="<?php echo empty($level['recurring']) ? '' : $level['recurring']; ?>" />
		<input type="hidden" name="site" value="<?php echo $site; ?>" />

		<input type="hidden" name="leaky_paywall_register_nonce" value="<?php echo wp_create_nonce('leaky-paywall-register-nonce'); ?>" />

	</form>

	<?php
	if ($level['price'] != 0) {
	?>
		<style>
			.leaky-paywall-registration-payment-container {
				display: none;
			}
		</style>
	<?php
	}
	?>

	<style>
		#leaky-paywall-registration-errors {
			display: none;
			padding: .75rem 1.25rem;
			margin: 1rem 0;
			border-radius: .25rem;
			color: #772b35;
			background: #fadddd;
			border: 1px solid #f8cfcf;
		}

		#leaky-paywall-registration-errors p {
			margin: 0;
			font-size: .75em;
		}
	</style>

	<?php do_action('leaky_paywall_after_registration_form', $gateways); ?>

<?php

	$content = ob_get_contents();
	ob_end_clean();

	return $content;

}
//add_shortcode('leaky_paywall_register_form', 'do_leaky_paywall_register_form');

