<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wponetap.com
 * @since      1.0.0
 *
 * @package    Accessibility_Onetap
 * @subpackage Accessibility_Onetap/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Accessibility_Onetap
 * @subpackage Accessibility_Onetap/admin
 * @author     OneTap <support@wponetap.com>
 */
class Accessibility_Onetap_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/accessibility-onetap-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/js/admin-menu.min.js', array( 'jquery' ), $this->version, true );

		// Localize admin.
		wp_localize_script(
			$this->plugin_name,
			'adminLocalize',
			array(
				'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
				'ajaxNonce' => wp_create_nonce( 'onetap-ajax-verification' ),
				'adminUrl'  => esc_url( admin_url() ),
			)
		);

		wp_enqueue_script( $this->plugin_name . '-admin-global', ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/js/admin-global.min.js', array( 'jquery' ), $this->version, true );
	}

	/**
	 * Display admin notice.
	 */
	public function display_admin_notice() {
		$get_cookie_disable_admin_notices = isset( $_COOKIE['onetap_disable_admin_notices'] ) ? sanitize_key( $_COOKIE['onetap_disable_admin_notices'] ) : '';
		if ( 'disable' === $get_cookie_disable_admin_notices ) {
			return;
		}
		?>
		<div class="notice-success notice-onetap notice is-dismissible">
			<div class="box">
				<div class="box-text">
					<p>
					<?php
					esc_html_e(
						"Hey!
					We’re happy that you’re using OneTap. We'd like to take this opportunity to kindly ask for a 5-star rating on WordPress.",
						'accessibility-onetap'
					);
					?>
						</p>
					<strong><?php esc_html_e( 'It would mean a lot to us.', 'accessibility-onetap' ); ?></strong>
				</div>
				<div class="box-btn">
					<a class="onetap-button" href="https://wordpress.org/support/plugin/accessibility-onetap/reviews/#new-post" target="_blank"><?php esc_html_e( 'Ok, you deserve it', 'accessibility-onetap' ); ?></a>
					<button class="outline already-did" type="button"><?php esc_html_e( 'Already did', 'accessibility-onetap' ); ?></button>
					<a class="onetap-button outline" href="https://wponetap.com/feedback" target="_blank"><?php esc_html_e( 'No, it’s not  good enough', 'accessibility-onetap' ); ?></a>
				</div>
			</div>
		</div>
		<style>
			.notice-onetap {
				padding: 0 !important;
				border: none;
				font-family: system-ui, -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Noto Sans, Ubuntu, Cantarell, Helvetica Neue, Oxygen, Fira Sans, Droid Sans, sans-serif !important;
			}

			.notice-onetap .box {
				background: #2563EB;
				border-radius: 8px;
				padding: 25px;
			}

			.notice-onetap .notice-dismiss:focus {
				box-shadow: none;
				outline: 0;
			}

			.notice-onetap .notice-dismiss::before {
				color: #FFFFFF;
			}

			.notice-onetap .box .box-text p,
			.notice-onetap .box .box-text strong {
				color: #FFFFFF;
				font-size: 16px;
				font-weight: 400;
				line-height: 1.5rem;
				padding: 0;
				margin: 0;
			}

			.notice-onetap .box .box-text strong {
				font-weight: 600;
			}

			.notice-onetap .box .box-btn {
				margin-top: 20px;
				display: flex;
				flex-wrap: wrap;
				gap: 10px;
			}

			.notice-onetap .box .box-btn button,
			.notice-onetap .box .box-btn a.onetap-button {
				font-size: 0.875rem;
				line-height: 1.25rem;
				fill: #2563EB;
				color: #2563EB;
				background-color: #FFFFFF;
				border-style: solid;
				border-width: 1px 1px 1px 1px;
				border-color: #FFFFFF;
				padding: 0.625rem 0.875rem 0.625rem 0.875rem;
				border-radius: 6px;
				font-weight: 500;
				display: inline-block;
				cursor: pointer;
				text-decoration: none;
			}

			.notice-onetap .box .box-btn button.outline,
			.notice-onetap .box .box-btn a.outline {
				fill: #FFFFFF;
				color: #FFFFFF;
				background: transparent;
			}
		</style>
		<?php
	}

	/**
	 * Callback function for AJAX to dismiss admin notices and set a cookie.
	 */
	public function dismiss_notice_ajax_callback() {
		// Security check: Ensure nonce is valid.
		check_ajax_referer( 'onetap-ajax-verification', 'mynonce' );

		// Set a cookie to disable admin notices for 5 days.
		setcookie( 'onetap_disable_admin_notices', 'disable', time() + ( 5 * 24 * 60 * 60 ) );

		// Terminate the AJAX request.
		wp_send_json( array( 'success' => true ) );
		wp_die();
	}

	/**
	 * Allow external domain for safe redirect.
	 *
	 * @param string[] $hosts Array of allowed hosts.
	 * @return string[] Modified array with external host.
	 */
	public function onetap_allow_external_redirect_host( $hosts ) {
		$hosts[] = 'wponetap.com';
		return $hosts;
	}

	/**
	 * Redirect users accessing a specific admin page to an external pricing URL.
	 *
	 * This function runs on 'admin_init' and checks whether the current admin page
	 * matches a specific plugin slug.
	 *
	 * @return void
	 */
	public function onetap_redirect_admin_page_to_pricing() {
		global $plugin_page, $pagenow;

		// Check if we are in the admin and on admin.php.
		if ( is_admin() && 'admin.php' === $pagenow && 'accessibility-onetap-pro' === $plugin_page ) {
			// Redirect.
			wp_safe_redirect( 'https://wponetap.com/pricing/', 301 );
			exit;
		}
	}

	/**
	 * Add a custom "Settings" link below the plugin description metadata
	 * (next to "Version | By | View details") in the Plugins admin screen.
	 *
	 * This function hooks into 'plugin_row_meta' and adds a link to the plugin's settings page
	 * only for this specific plugin file.
	 *
	 * @param array  $links Array of existing plugin row meta links.
	 * @param string $file  The plugin file path (relative to the plugins directory).
	 *
	 * @return array Modified array of plugin row meta links.
	 */
	public function add_row_meta( $links, $file ) {

		// Only add the settings link for this specific plugin.
		if ( 'accessibility-onetap/accessibility-onetap.php' === $file ) {
			$settings_url  = admin_url( 'admin.php?page=accessibility-onetap-settings' );
			$settings_link = '<a href="' . esc_url( $settings_url ) . '">' . esc_html__( 'Settings', 'accessibility-onetap' ) . '</a>';

			// Append the custom settings link to the plugin meta row.
			$links[] = $settings_link;
		}

		return $links;
	}
}
