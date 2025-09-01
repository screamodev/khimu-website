<?php
/**
 * Accessibility Settings Option for Onetap.
 *
 * @link       https://wponetap.com
 * @since      1.0.0
 *
 * @package    Accessibility_Onetap_Settings_Options
 * @subpackage Accessibility_Onetap_Settings_Options/admin
 */

/**
 * Accessibility Settings Option for Onetap.
 *
 * Handles the settings related to accessibility in the Onetap Pro plugin.
 *
 * @package    Accessibility_Onetap_Settings_Options
 * @subpackage Accessibility_Onetap_Settings_Options/admin
 * @author     OneTap <support@wponetap.com>
 */
class Accessibility_Onetap_Settings_Options {

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
	 * Settings api.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $settings_api    The options of this plugin.
	 */
	private $settings_api;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name      The name of this plugin.
	 * @param      string $version          The version of this plugin.
	 * @param      object $settings_manager The settings manager of this plugin.
	 */
	public function __construct( $plugin_name, $version, $settings_manager ) {

		$this->plugin_name  = $plugin_name;
		$this->version      = $version;
		$this->settings_api = $settings_manager;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name . '-admin', ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/css/accessibility-onetap-admin-menu.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Returns the URL of the custom SVG icon for the menu.
	 *
	 * @return string The URL to the SVG icon.
	 */
	public function get_custom_svg_icon() {
		// Assuming the SVG icon is saved in your plugin's 'assets' directory.
		return ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/icon.svg';
	}

	/**
	 * Registers the top-level admin menu page and adds a submenu page for 'OneTap'.
	 *
	 * This function uses the WordPress add_menu_page() and add_submenu_page() functions to create
	 * the necessary admin menu structure. It also sets up the callback functions for
	 * the top-level and submenu pages.
	 *
	 * @return void
	 */
	public function register_admin_menu_page() {
		add_menu_page(
			__( 'OneTap', 'accessibility-onetap' ), // Page title.
			__( 'OneTap', 'accessibility-onetap' ), // Menu title.
			'manage_options', // Capability required.
			'accessibility-onetap-settings', // Menu slug.
			array( $this, 'callback_template_for_settings' ), // Callback function.
			ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/menu.svg', // URL icon SVG.
			30 // $position.
		);

		add_submenu_page(
			'accessibility-onetap-settings', // Parent menu slug.
			__( 'Settings', 'accessibility-onetap' ), // Page title.
			__( 'Settings', 'accessibility-onetap' ), // Menu title.
			'manage_options', // Capability required.
			'accessibility-onetap-settings', // Menu slug.
			array( $this, 'callback_template_for_settings' ) // Callback function.
		);

		add_submenu_page(
			'accessibility-onetap-settings', // Parent menu slug.
			__( 'Modules', 'accessibility-onetap' ), // Page title.
			__( 'Modules', 'accessibility-onetap' ), // Menu title.
			'manage_options', // Capability required.
			'accessibility-onetap-modules', // Menu slug.
			array( $this, 'callback_template_for_modules' ) // Callback function.
		);

		add_submenu_page(
			'accessibility-onetap-settings', // Parent menu slug.
			__( 'Get PRO', 'accessibility-onetap' ), // Page title.
			__( 'Get PRO', 'accessibility-onetap' ), // Menu title.
			'manage_options', // Capability required.
			'accessibility-onetap-pro', // Menu slug.
			array( $this, 'callback_template_for_ge_pro' ) // Callback function.
		);
	}

	/**
	 * Loads the template for the 'Settings' menu page in the plugin.
	 *
	 * This function constructs the path to the template file located
	 * in the plugin directory and includes it if it exists.
	 */
	public function callback_template_for_settings() {
		// Define the path to the template file.
		$template_path = plugin_dir_path( __FILE__ ) . 'partials/settings.php';

		// Check if the template file exists.
		if ( file_exists( $template_path ) ) {
			// Include the template file if it exists.
			include $template_path;
		}
	}

	/**
	 * Loads the template for the 'Modules' menu page in the plugin.
	 *
	 * This function constructs the path to the template file located
	 * in the plugin directory and includes it if it exists.
	 */
	public function callback_template_for_modules() {
		// Define the path to the template file.
		$template_path = plugin_dir_path( __FILE__ ) . 'partials/modules.php';

		// Check if the template file exists.
		if ( file_exists( $template_path ) ) {
			// Include the template file if it exists.
			include $template_path;
		}
	}

	/**
	 * Loads the template for the 'Get PRO' menu page in the plugin.
	 *
	 * This function constructs the path to the template file located
	 * in the plugin directory and includes it if it exists.
	 */
	public function callback_template_for_ge_pro() {
		echo '<div class="wrap"></div>';
	}

	/**
	 * Remove notifications.
	 */
	public function hide_notifications_for_onetap_page() {
		if ( is_admin() && get_admin_page_parent() === 'accessibility-onetap-settings' ) {
			remove_all_actions( 'admin_notices' );
		}
	}

	/**
	 * Initialize the admin settings.
	 *
	 * This method sets the sections and fields for the settings API and initializes them.
	 *
	 * @return void
	 */
	public function admin_init() {

		// Set the settings api.
		$this->settings_api->set_sections( $this->get_settings_api_sections() );
		$this->settings_api->set_fields( $this->get_settings_api_fields() );

		// Initialize settings api.
		$this->settings_api->admin_init();
	}

	/**
	 * Retrieve the settings sections for the plugin.
	 *
	 * This method returns an array of sections used in the settings API.
	 * Each section contains an ID and a title.
	 *
	 * @return array The array of settings sections.
	 */
	public function get_settings_api_sections() {
		$sections = array(
			array(
				'id'    => 'onetap_settings',
				'title' => __( 'Settings', 'accessibility-onetap' ),
			),
			array(
				'id'    => 'onetap_modules',
				'title' => __( 'Modules', 'accessibility-onetap' ),
			),
		);
		return $sections;
	}

	/**
	 * Returns all the settings fields
	 *
	 * @return array settings fields
	 */
	public function get_settings_api_fields() {
		$settings_fields = array(
			'onetap_settings' => array(
				array(
					'name'              => 'icons',
					'label'             => __( 'Icons', 'accessibility-onetap' ),
					'label_checkbox'    => __( 'Icons', 'accessibility-onetap' ),
					'site_title'        => __( 'Design', 'accessibility-onetap' ),
					'site_description'  => __( 'Customize your accessibility button’s color, icon, and size to match your brand.', 'accessibility-onetap' ) . ' <a href="https://wponetap.com/tutorial/customize-the-toolbar-icon/" target="_blank">' . __( 'See Documentation', 'accessibility-onetap' ) . '</a>',
					'desc'              => '',
					'type'              => 'radio',
					'callback'          => 'callback_template_radio_icons',
					'default'           => Accessibility_Onetap_Config::get_setting( 'icons' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'size',
					'label'             => __( 'Size', 'accessibility-onetap' ),
					'label_checkbox'    => __( 'Size', 'accessibility-onetap' ),
					'desc'              => '',
					'type'              => 'radio',
					'callback'          => 'callback_template_radio_size',
					'default'           => Accessibility_Onetap_Config::get_setting( 'size' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'border',
					'label'             => __( 'Border', 'accessibility-onetap' ),
					'label_checkbox'    => __( 'Border', 'accessibility-onetap' ),
					'desc'              => '',
					'type'              => 'radio',
					'callback'          => 'callback_template_radio_border',
					'default'           => Accessibility_Onetap_Config::get_setting( 'border' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'color',
					'label'             => __( 'Color', 'accessibility-onetap' ),
					'site_title'        => __( 'Colors', 'accessibility-onetap' ),
					'site_description'  => __( 'Set your own branding colors to personalize the plugin’s appearance.', 'accessibility-onetap' ) . ' <a href="https://wponetap.com/color-options/" target="_blank">' . __( 'See Documentation', 'accessibility-onetap' ) . '</a>',
					'desc'              => '',
					'type'              => 'color',
					'callback'          => 'callback_template_color',
					'default'           => Accessibility_Onetap_Config::get_setting( 'color' ),
					'sanitize_callback' => 'sanitize_hex_color',
				),
				array(
					'name'              => 'position-top-bottom',
					'label'             => __( 'Top/bottom position (px)', 'accessibility-onetap' ),
					'site_title'        => __( 'Position', 'accessibility-onetap' ),
					'site_description'  => __( 'Adjust the position of widgets to fit your layout preferences.', 'accessibility-onetap' ) . ' <a href="https://wponetap.com/position/" target="_blank">' . __( 'See Documentation', 'accessibility-onetap' ) . '</a>',
					'desc'              => '',
					'min'               => 0,
					'max'               => 1000,
					'step'              => 1,
					'type'              => 'number',
					'callback'          => 'callback_template_number_top_bottom',
					'default'           => Accessibility_Onetap_Config::get_setting( 'position_top_bottom' ),
					'sanitize_callback' => 'absint',
				),
				array(
					'name'              => 'position-left-right',
					'label'             => __( 'Left/right position (px)', 'accessibility-onetap' ),
					'desc'              => '',
					'min'               => 0,
					'max'               => 1000,
					'step'              => 1,
					'type'              => 'number',
					'callback'          => 'callback_template_number_left_right',
					'default'           => Accessibility_Onetap_Config::get_setting( 'position_left_right' ),
					'sanitize_callback' => 'absint',
				),
				array(
					'name'              => 'widge-position',
					'label'             => __( 'Widget Position', 'accessibility-onetap' ),
					'desc'              => '',
					'type'              => 'select',
					'callback'          => 'callback_template_widget_position',
					'default'           => Accessibility_Onetap_Config::get_setting( 'widget_position' ),
					'sanitize_callback' => 'sanitize_text_field',
					'options'           => array(
						'middle-right' => __( 'Middle right', 'accessibility-onetap' ),
						'middle-left'  => __( 'Middle left', 'accessibility-onetap' ),
						'bottom-right' => __( 'Bottom right', 'accessibility-onetap' ),
						'bottom-left'  => __( 'Bottom left', 'accessibility-onetap' ),
						'top-right'    => __( 'Top right', 'accessibility-onetap' ),
						'top-left'     => __( 'Top left', 'accessibility-onetap' ),
					),
				),
				array(
					'name'              => 'position-top-bottom-tablet',
					'label'             => __( 'Top/bottom position (px)', 'accessibility-onetap' ),
					'site_title'        => __( 'Position', 'accessibility-onetap' ),
					'site_description'  => __( 'Adjust the position of widgets to fit your layout preferences.', 'accessibility-onetap' ) . ' <a href="https://wponetap.com/position/" target="_blank">' . __( 'See Documentation', 'accessibility-onetap' ) . '</a>',
					'desc'              => '',
					'min'               => 0,
					'max'               => 1000,
					'step'              => 1,
					'type'              => 'number',
					'callback'          => 'callback_template_number_top_bottom',
					'default'           => Accessibility_Onetap_Config::get_setting( 'position_top_bottom_tablet' ),
					'sanitize_callback' => 'absint',
				),
				array(
					'name'              => 'position-left-right-tablet',
					'label'             => __( 'Left/right position (px)', 'accessibility-onetap' ),
					'desc'              => '',
					'min'               => 0,
					'max'               => 1000,
					'step'              => 1,
					'type'              => 'number',
					'callback'          => 'callback_template_number_left_right',
					'default'           => Accessibility_Onetap_Config::get_setting( 'position_left_right_tablet' ),
					'sanitize_callback' => 'absint',
				),
				array(
					'name'              => 'widge-position-tablet',
					'label'             => __( 'Widget Position', 'accessibility-onetap' ),
					'desc'              => '',
					'type'              => 'select',
					'callback'          => 'callback_template_widget_position',
					'default'           => Accessibility_Onetap_Config::get_setting( 'widget_position_tablet' ),
					'sanitize_callback' => 'sanitize_text_field',
					'options'           => array(
						'middle-right' => __( 'Middle right', 'accessibility-onetap' ),
						'middle-left'  => __( 'Middle left', 'accessibility-onetap' ),
						'bottom-right' => __( 'Bottom right', 'accessibility-onetap' ),
						'bottom-left'  => __( 'Bottom left', 'accessibility-onetap' ),
						'top-right'    => __( 'Top right', 'accessibility-onetap' ),
						'top-left'     => __( 'Top left', 'accessibility-onetap' ),
					),
				),
				array(
					'name'              => 'position-top-bottom-mobile',
					'label'             => __( 'Top/bottom position (px)', 'accessibility-onetap' ),
					'site_title'        => __( 'Position', 'accessibility-onetap' ),
					'site_description'  => __( 'Adjust the position of widgets to fit your layout preferences.', 'accessibility-onetap' ) . ' <a href="https://wponetap.com/position/" target="_blank">' . __( 'See Documentation', 'accessibility-onetap' ) . '</a>',
					'desc'              => '',
					'min'               => 0,
					'max'               => 1000,
					'step'              => 1,
					'type'              => 'number',
					'callback'          => 'callback_template_number_top_bottom',
					'default'           => Accessibility_Onetap_Config::get_setting( 'position_top_bottom_mobile' ),
					'sanitize_callback' => 'absint',
				),
				array(
					'name'              => 'position-left-right-mobile',
					'label'             => __( 'Left/right position (px)', 'accessibility-onetap' ),
					'desc'              => '',
					'min'               => 0,
					'max'               => 1000,
					'step'              => 1,
					'type'              => 'number',
					'callback'          => 'callback_template_number_left_right',
					'default'           => Accessibility_Onetap_Config::get_setting( 'position_left_right_mobile' ),
					'sanitize_callback' => 'absint',
				),
				array(
					'name'              => 'widge-position-mobile',
					'label'             => __( 'Widget Position', 'accessibility-onetap' ),
					'desc'              => '',
					'type'              => 'select',
					'callback'          => 'callback_template_widget_position',
					'default'           => Accessibility_Onetap_Config::get_setting( 'widget_position_mobile' ),
					'sanitize_callback' => 'sanitize_text_field',
					'options'           => array(
						'middle-right' => __( 'Middle right', 'accessibility-onetap' ),
						'middle-left'  => __( 'Middle left', 'accessibility-onetap' ),
						'bottom-right' => __( 'Bottom right', 'accessibility-onetap' ),
						'bottom-left'  => __( 'Bottom left', 'accessibility-onetap' ),
						'top-right'    => __( 'Top right', 'accessibility-onetap' ),
						'top-left'     => __( 'Top left', 'accessibility-onetap' ),
					),
				),
				array(
					'name'              => 'language',
					'label'             => __( 'Default Language', 'accessibility-onetap' ),
					'site_title'        => __( 'Language', 'accessibility-onetap' ),
					'site_description'  => __( 'Choose your preferred language for the plugin’s interface.', 'accessibility-onetap' ) . ' <a href="https://wponetap.com/language-options/" target="_blank">' . __( 'See Documentation', 'accessibility-onetap' ) . '</a>',
					'desc'              => '',
					'type'              => 'select',
					'callback'          => 'callback_template_language',
					'default'           => Accessibility_Onetap_Config::get_setting( 'language' ),
					'sanitize_callback' => 'sanitize_text_field',
					'options'           => array(
						'en'    => __( 'English', 'accessibility-onetap' ),
						'de'    => __( 'Deutsch', 'accessibility-onetap' ),
						'es'    => __( 'Español', 'accessibility-onetap' ),
						'fr'    => __( 'Français', 'accessibility-onetap' ),
						'it'    => __( 'Italiano', 'accessibility-onetap' ),
						'pl'    => __( 'Polski', 'accessibility-onetap' ),
						'se'    => __( 'Svenska', 'accessibility-onetap' ),
						'fi'    => __( 'Suomi', 'accessibility-onetap' ),
						'pt'    => __( 'Português', 'accessibility-onetap' ),
						'ro'    => __( 'Română', 'accessibility-onetap' ),
						'si'    => __( 'Slovenščina', 'accessibility-onetap' ),
						'sk'    => __( 'Slovenčina', 'accessibility-onetap' ),
						'nl'    => __( 'Nederlands', 'accessibility-onetap' ),
						'dk'    => __( 'Dansk', 'accessibility-onetap' ),
						'gr'    => __( 'Ελληνικά', 'accessibility-onetap' ),
						'cz'    => __( 'Čeština', 'accessibility-onetap' ),
						'hu'    => __( 'Magyar', 'accessibility-onetap' ),
						'lt'    => __( 'Lietuvių', 'accessibility-onetap' ),
						'lv'    => __( 'Latviešu', 'accessibility-onetap' ),
						'ee'    => __( 'Eesti', 'accessibility-onetap' ),
						'hr'    => __( 'Hrvatski', 'accessibility-onetap' ),
						'ie'    => __( 'Gaeilge', 'accessibility-onetap' ),
						'bg'    => __( 'Български', 'accessibility-onetap' ),
						'no'    => __( 'Norsk', 'accessibility-onetap' ),
						'tr'    => __( 'Türkçe', 'accessibility-onetap' ),
						'id'    => __( 'Bahasa Indonesia', 'accessibility-onetap' ),
						'pt-br' => __( 'Português (Brasil)', 'accessibility-onetap' ),
						'ja'    => __( '日本語', 'accessibility-onetap' ),
						'ko'    => __( '한국어', 'accessibility-onetap' ),
						'zh'    => __( '简体中文', 'accessibility-onetap' ),
						'ar'    => __( 'العربية', 'accessibility-onetap' ),
						'ru'    => __( 'Русский', 'accessibility-onetap' ),
						'hi'    => __( 'हिन्दी', 'accessibility-onetap' ),
						'uk'    => __( 'Українська', 'accessibility-onetap' ),
						'sr'    => __( 'Srpski', 'accessibility-onetap' ),
					),			
				),
				array(
					'name'              => 'hide-powered-by-onetap',
					'label'             => __( 'Hide "Powered by OneTap"', 'accessibility-onetap' ),
					'desc'              => __( 'Easily remove the "Powered by OneTap" text from the sidebar on the frontend of your website.', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/hide-powered-by-onetap.svg',
					'type'              => 'checkbox',
					'status'            => true,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'hide_powered_by_onetap' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
			),
			'onetap_modules'  => array(
				array(
					'name'              => 'accessibility-profiles-title',
					'group_title'       => __( 'Accessibility Profiles', 'accessibility-onetap' ),
					'group_description' => __( 'Smart solutions to enhance accessibility and improve overall usability.', 'accessibility-onetap' ),
					'type'              => 'text',
					'callback'          => 'callback_template_general_module_title',
					'anchor'            => 'anchorAccessibilityProfiles',
					'default'           => true,
				),
				array(
					'name'              => 'accessibility-profiles',
					'label'             => __( 'Accessibility Profiles', 'accessibility-onetap' ),
					'desc'              => __( 'Ready-to-use accessibility profiles for different user needs.', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/accessibility-profiles.svg',
					'type'              => 'checkbox',
					'status'            => true,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'accessibility_profiles' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'content-modules-title',
					'group_title'       => __( 'Content Modules', 'accessibility-onetap' ),
					'group_description' => __( 'Versatile tools to customize and enhance overall accessibility and usability.', 'accessibility-onetap' ),
					'type'              => 'text',
					'callback'          => 'callback_template_general_module_title',
					'anchor'            => 'anchorContentlModules',
					'default'           => true,
				),
				array(
					'name'              => 'bigger-text',
					'label'             => __( 'Bigger Text', 'accessibility-onetap' ),
					'desc'              => __( 'Easily enlarge text for improved readability and accessibility for all', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/bigger-text.svg',
					'type'              => 'checkbox',
					'status'            => false,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'bigger_text' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'cursor',
					'label'             => __( 'Cursor', 'accessibility-onetap' ),
					'desc'              => __( 'Enhance visibility with a larger cursor for easier navigation and control', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/cursor.svg',
					'type'              => 'checkbox',
					'status'            => false,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'cursor' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'line-height',
					'label'             => __( 'Line Height', 'accessibility-onetap' ),
					'desc'              => __( 'Adjust line spacing for better readability and improved text clarity', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/line-height.svg',
					'type'              => 'checkbox',
					'status'            => false,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'line_height' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'letter-spacing',
					'label'             => __( 'Letter Spacing', 'accessibility-onetap' ),
					'desc'              => __( 'Adjust letter spacing for improved readability', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/letter-spacing.svg',
					'type'              => 'checkbox',
					'status'            => false,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'letter_spacing' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'readable-font',
					'label'             => __( 'Readable Font', 'accessibility-onetap' ),
					'desc'              => __( 'Switch to a clearer, easy-to-read font for improved text accessibility', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/readable-font.svg',
					'type'              => 'checkbox',
					'status'            => false,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'readable_font' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'dyslexic-font',
					'label'             => __( 'Dyslexic Font', 'accessibility-onetap' ),
					'desc'              => __( 'Use a specialized font designed to enhance readability for dyslexic users', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/dyslexic-font.svg',
					'type'              => 'checkbox',
					'status'            => true,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'dyslexic_font' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'text-align',
					'label'             => __( 'Align Text', 'accessibility-onetap' ),
					'desc'              => __( 'Adjust text alignment for improved structure and readability', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/align-text.svg',
					'type'              => 'checkbox',
					'status'            => false,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'text_align' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'text-magnifier',
					'label'             => __( 'Text Magnifier', 'accessibility-onetap' ),
					'desc'              => __( 'Magnify selected text for enhanced readability and accessibility', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/text-magnifier.svg',
					'type'              => 'checkbox',
					'status'            => true,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'text_magnifier' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'highlight-links',
					'label'             => __( 'Highlight Links', 'accessibility-onetap' ),
					'desc'              => __( 'Easily identify clickable links with visual enhancements for better navigation', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/highlight-links.svg',
					'type'              => 'checkbox',
					'status'            => false,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'highlight_links' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'     => 'content-modules-title-save-changes',
					'type'     => 'text',
					'callback' => 'callback_template_save_changes',
				),
				array(
					'name'              => 'color-modules',
					'group_title'       => __( 'Colors', 'accessibility-onetap' ),
					'group_description' => __( 'Options to adjust color settings for improved visibility and comfort.', 'accessibility-onetap' ),
					'type'              => 'text',
					'callback'          => 'callback_template_general_module_title',
					'anchor'            => 'anchorModulesColors',
					'default'           => true,
				),
				array(
					'name'              => 'invert-colors',
					'label'             => __( 'Invert Colors', 'accessibility-onetap' ),
					'desc'              => __( 'Swap text and background colors for better contrast and readability', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/invert-colors.svg',
					'type'              => 'checkbox',
					'status'            => false,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'invert_colors' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'brightness',
					'label'             => __( 'Brightness', 'accessibility-onetap' ),
					'desc'              => __( 'Adjust screen brightness to reduce glare and enhance text visibility', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/brightness.svg',
					'type'              => 'checkbox',
					'status'            => false,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'brightness' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'contrast',
					'label'             => __( 'Contrast', 'accessibility-onetap' ),
					'desc'              => __( 'Fine-tune color contrast for clearer text and improved readability', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/contrast.svg',
					'type'              => 'checkbox',
					'status'            => true,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'contrast' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'grayscale',
					'label'             => __( 'Grayscale', 'accessibility-onetap' ),
					'desc'              => __( 'Convert display to black and white for reduced visual clutter and better focus', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/grayscale.svg',
					'type'              => 'checkbox',
					'status'            => false,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'grayscale' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'saturnation',
					'label'             => __( 'Saturnation', 'accessibility-onetap' ),
					'desc'              => __( 'Adjust color saturation for a more vivid or subdued visual experience', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/saturnation.svg',
					'type'              => 'checkbox',
					'status'            => true,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'saturation' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'     => 'color-modules-save-changes',
					'type'     => 'text',
					'callback' => 'callback_template_save_changes',
				),
				array(
					'name'              => 'orientation-modules',
					'group_title'       => __( 'Orientation', 'accessibility-onetap' ),
					'group_description' => __( 'Tools to enhance ease of movement and accessibility across the site.', 'accessibility-onetap' ),
					'type'              => 'text',
					'callback'          => 'callback_template_general_module_title',
					'anchor'            => 'anchorOrientation',
					'default'           => true,
				),
				array(
					'name'              => 'reading-line',
					'label'             => __( 'Reading Line', 'accessibility-onetap' ),
					'desc'              => __( 'Highlight the current line to track reading progress and improve focus', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/reading-line.svg',
					'type'              => 'checkbox',
					'status'            => false,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'reading_line' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'keyboard-navigation',
					'label'             => __( 'Keyboard Navigation', 'accessibility-onetap' ),
					'desc'              => __( 'Navigate the site using only the keyboard', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/keyboard-navigation.svg',
					'type'              => 'checkbox',
					'status'            => true,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'keyboard_navigation' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'highlight-titles',
					'label'             => __( 'Highlight Titles', 'accessibility-onetap' ),
					'desc'              => __( 'Highlight titles for better recognition', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/highlight-titles.svg',
					'type'              => 'checkbox',
					'status'            => true,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'highlight_titles' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'reading-mask',
					'label'             => __( 'Reading Mask', 'accessibility-onetap' ),
					'desc'              => __( 'Use a mask to focus on a specific area of text and reduce distractions', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/reading-mask.svg',
					'type'              => 'checkbox',
					'status'            => false,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'reading_mask' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'hide-images',
					'label'             => __( 'Hide Images', 'accessibility-onetap' ),
					'desc'              => __( 'Remove distracting images for a cleaner, more focused browsing experience', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/hide-images.svg',
					'type'              => 'checkbox',
					'status'            => false,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'hide_images' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'highlight-all',
					'label'             => __( 'Highlight All', 'accessibility-onetap' ),
					'desc'              => __( 'Highlight elements when hovered over', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/highlight-all.svg',
					'type'              => 'checkbox',
					'status'            => false,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'highlight_all' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'read-page',
					'label'             => __( 'Read Page', 'accessibility-onetap' ),
					'desc'              => __( 'Automatically read aloud the page content for hands-free accessibility', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/read-page.svg',
					'type'              => 'checkbox',
					'status'            => true,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'read_page' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'mute-sounds',
					'label'             => __( 'Mute Sounds', 'accessibility-onetap' ),
					'desc'              => __( 'Easily mute website sounds for a distraction-free browsing experience', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/mute-sounds.svg',
					'type'              => 'checkbox',
					'status'            => true,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'mute_sounds' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'              => 'stop-animations',
					'label'             => __( 'Stop Animations', 'accessibility-onetap' ),
					'desc'              => __( 'Disable animations to reduce distractions', 'accessibility-onetap' ),
					'icon'              => ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/stop-animations.svg',
					'type'              => 'checkbox',
					'status'            => false,
					'callback'          => 'callback_template_checkbox',
					'default'           => Accessibility_Onetap_Config::get_module( 'stop_animations' ),
					'sanitize_callback' => 'sanitize_text_field',
				),
				array(
					'name'     => 'orientation-modules-save-changes',
					'type'     => 'text',
					'callback' => 'callback_template_save_changes',
				),
			),
		);

		return $settings_fields;
	}

	/**
	 * Get all the pages
	 *
	 * @return array page names with key value pairs
	 */
	public function get_pages() {
		$pages         = get_pages();
		$pages_options = array();
		if ( $pages ) {
			foreach ( $pages as $page ) {
				$pages_options[ $page->ID ] = $page->post_title;
			}
		}

		return $pages_options;
	}
}
