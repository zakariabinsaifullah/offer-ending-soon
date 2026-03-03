<?php
/**
 * Plugin Name: Offer Ending Soon
 * Description: A simple plugin to display an evergreen countdown banner.
 * Version: 1.0.0
 * Author: Your Name
 * Text Domain: offer-ending-soon
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Offer_Ending_Soon {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_body_open', array( $this, 'display_banner' ) );
		add_shortcode( 'offer_ending_soon', array( $this, 'render_shortcode' ) );
	}

	public function add_settings_page() {
		add_options_page(
			'Offer Ending Soon',
			'Offer Ending Soon',
			'manage_options',
			'offer-ending-soon',
			array( $this, 'render_settings_page' )
		);
	}

	public function register_settings() {
		register_setting( 'oes_settings_group', 'oes_title' );
		register_setting( 'oes_settings_group', 'oes_duration' );
		register_setting( 'oes_settings_group', 'oes_btn_label' );
		register_setting( 'oes_settings_group', 'oes_btn_link' );
		register_setting( 'oes_settings_group', 'oes_bg_color' );
		register_setting( 'oes_settings_group', 'oes_text_color' );
		register_setting( 'oes_settings_group', 'oes_display_location' );
	}

	public function render_settings_page() {
		?>
		<div class="wrap">
			<h1>Offer Ending Soon Settings</h1>
			<form method="post" action="options.php">
				<?php settings_fields( 'oes_settings_group' ); ?>
				<?php do_settings_sections( 'oes_settings_group' ); ?>
				
				<table class="form-table">
					<tr valign="top">
						<th scope="row">Offer Title</th>
						<td>
							<input type="text" name="oes_title" value="<?php echo esc_attr( get_option( 'oes_title', 'Lifetime Subscription 50% Off' ) ); ?>" class="regular-text" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Offer Duration (Hours)</th>
						<td>
							<input type="number" name="oes_duration" value="<?php echo esc_attr( get_option( 'oes_duration', 5 ) ); ?>" class="regular-text" min="1" />
							<p class="description">The number of hours the countdown will run for before resetting.</p>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Button Label</th>
						<td>
							<input type="text" name="oes_btn_label" value="<?php echo esc_attr( get_option( 'oes_btn_label', 'Get Offer' ) ); ?>" class="regular-text" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Button Link</th>
						<td>
							<input type="url" name="oes_btn_link" value="<?php echo esc_attr( get_option( 'oes_btn_link', '#' ) ); ?>" class="regular-text" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Background Color</th>
						<td>
							<input type="color" name="oes_bg_color" value="<?php echo esc_attr( get_option( 'oes_bg_color', '#1e73be' ) ); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Text Color</th>
						<td>
							<input type="color" name="oes_text_color" value="<?php echo esc_attr( get_option( 'oes_text_color', '#ffffff' ) ); ?>" />
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Display Location</th>
						<td>
							<select name="oes_display_location">
								<option value="top" <?php selected( get_option( 'oes_display_location', 'top' ), 'top' ); ?>>Top of Site (wp_body_open)</option>
								<option value="shortcode" <?php selected( get_option( 'oes_display_location', 'top' ), 'shortcode' ); ?>>Shortcode Only</option>
							</select>
							<p class="description">If 'Shortcode Only' is selected, you can use <code>[offer_ending_soon]</code> to display the banner anywhere.</p>
						</td>
					</tr>
				</table>
				
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'oes-style', plugins_url( 'assets/css/style.css', __FILE__ ), array(), '1.0.0' );
		wp_enqueue_script( 'oes-script', plugins_url( 'assets/js/script.js', __FILE__ ), array(), '1.0.0', true );

		$duration = floatval( get_option( 'oes_duration', 5 ) );
		wp_localize_script( 'oes-script', 'oesData', array(
			'durationHours' => $duration
		) );
		
		// Inline styles for custom colors
		$bg_color   = get_option( 'oes_bg_color', '#1e73be' );
		$text_color = get_option( 'oes_text_color', '#ffffff' );
		$custom_css = "
			.oes-banner {
				background-color: {$bg_color};
				color: {$text_color};
			}
			.oes-banner .oes-btn {
				background-color: {$text_color};
				color: {$bg_color};
			}
		";
		wp_add_inline_style( 'oes-style', $custom_css );
	}

	public function display_banner() {
		if ( get_option( 'oes_display_location', 'top' ) === 'top' ) {
			echo $this->get_banner_html();
		}
	}

	public function render_shortcode() {
		return $this->get_banner_html();
	}

	private function get_banner_html() {
		$title     = get_option( 'oes_title', 'Lifetime Subscription 50% Off' );
		$btn_label = get_option( 'oes_btn_label', 'Get Offer' );
		$btn_link  = get_option( 'oes_btn_link', '#' );

		ob_start();
		?>
		<div class="oes-banner">
			<div class="oes-banner-inner">
				<div class="oes-title"><?php echo esc_html( $title ); ?></div>
				<div class="oes-timer">
					<div class="oes-time-block"><span class="oes-hours">00</span><span class="oes-label">Hrs</span></div><span class="oes-sep">:</span>
					<div class="oes-time-block"><span class="oes-minutes">00</span><span class="oes-label">Min</span></div><span class="oes-sep">:</span>
					<div class="oes-time-block"><span class="oes-seconds">00</span><span class="oes-label">Sec</span></div>
				</div>
				<?php if ( ! empty( $btn_label ) ) : ?>
					<a href="<?php echo esc_url( $btn_link ); ?>" class="oes-btn"><?php echo esc_html( $btn_label ); ?></a>
				<?php endif; ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}

new Offer_Ending_Soon();
