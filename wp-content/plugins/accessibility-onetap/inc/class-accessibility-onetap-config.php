<?php
/**
 * Onetap Pro Config Class.
 *
 * This class manages the configuration settings for Onetap Pro.
 *
 * @link       https://wponetap.com
 * @since      1.0.0
 *
 * @package    Accessibility_Onetap_Config
 */

/**
 * Class Accessibility_Onetap_Config
 *
 * Manages configuration settings for Onetap Pro.
 */
class Accessibility_Onetap_Config {

	/**
	 * Static array property to hold configuration settings.
	 *
	 * @var array
	 */
	public static $settings = array(
		'icons'                      => 'design1',
		'size'                       => 'design-size2',
		'border'                     => 'design-border2',
		'language'                   => 'en',
		'color'                      => '#2563eb',
		'position_top_bottom'        => 15,
		'position_left_right'        => 15,
		'widget_position'            => 'bottom-right',
		'position_top_bottom_tablet' => 15,
		'position_left_right_tablet' => 15,
		'widget_position_tablet'     => 'bottom-right',
		'position_top_bottom_mobile' => 15,
		'position_left_right_mobile' => 15,
		'widget_position_mobile'     => 'bottom-right',
		'hide_powered_by_onetap'     => 'off',
		'license'                    => '',
	);

	/**
	 * Static array property to hold module configurations.
	 *
	 * @var array
	 */
	public static $modules = array(
		'accessibility_profiles' => 'off',
		'bigger_text'            => 'on',
		'cursor'                 => 'on',
		'line_height'            => 'on',
		'letter_spacing'         => 'on',
		'readable_font'          => 'on',
		'dyslexic_font'          => 'off',
		'text_align'             => 'on',
		'text_magnifier'         => 'off',
		'highlight_links'        => 'on',
		'invert_colors'          => 'on',
		'brightness'             => 'on',
		'contrast'               => 'off',
		'grayscale'              => 'on',
		'saturation'             => 'off',
		'reading_line'           => 'on',
		'keyboard_navigation'    => 'off',
		'highlight_titles'       => 'off',
		'reading_mask'           => 'on',
		'hide_images'            => 'on',
		'highlight_all'          => 'on',
		'read_page'              => 'off',
		'mute_sounds'            => 'off',
		'stop_animations'        => 'on',
	);

	/**
	 * Static method to get a setting value.
	 *
	 * @param string $key The setting key to retrieve.
	 * @return mixed The setting value or null if key doesn't exist.
	 */
	public static function get_setting( $key ) {
		return isset( self::$settings[ $key ] ) ? self::$settings[ $key ] : null; // Return setting value or null.
	}

	/**
	 * Static method to get a module configuration value.
	 *
	 * @param string $key The module key to retrieve.
	 * @return mixed The module value or null if key doesn't exist.
	 */
	public static function get_module( $key ) {
		return isset( self::$modules[ $key ] ) ? self::$modules[ $key ] : null; // Return module value or null.
	}
}

// Example usage:
// Accessing static array values without creating an object.
// echo Accessibility_Onetap_Config::get_setting('language'); // Output: en.
// echo Accessibility_Onetap_Config::get_module('bigger_text'); // Output: on.
