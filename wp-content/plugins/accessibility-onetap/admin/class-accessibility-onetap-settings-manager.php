<?php
/**
 * Accessibility Settings Manager for Onetap.
 *
 * @link       https://wponetap.com
 * @since      1.0.0
 *
 * @package    Accessibility_Onetap_Settings_Manager
 * @subpackage Accessibility_Onetap_Settings_Manager/admin
 */

/**
 * Accessibility Settings Manager for Onetap.
 *
 * Handles the settings related to accessibility in the Onetap Pro plugin.
 *
 * @package    Accessibility_Onetap_Settings_Manager
 * @subpackage Accessibility_Onetap_Settings_Manager/admin
 * @author     OneTap <support@wponetap.com>
 */
class Accessibility_Onetap_Settings_Manager {

	/**
	 * Settings sections array.
	 *
	 * @var array
	 */
	protected $settings_sections = array();

	/**
	 * Settings fields array.
	 *
	 * @var array
	 */
	protected $settings_fields = array();

	/**
	 * Initialize __construct.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	/**
	 * Enqueue scripts and styles for the admin area.
	 *
	 * This function ensures that necessary scripts and styles are loaded on
	 * specific admin pages of the plugin. It loads the WordPress color picker,
	 * media uploader, jQuery, and a custom JavaScript file for managing settings.
	 *
	 * @param string $hook The current admin page hook suffix.
	 */
	public function admin_enqueue_scripts( $hook ) {
		// Enqueue the WordPress color picker style.
		wp_enqueue_style( 'wp-color-picker' );

		// Enqueue the media uploader script.
		wp_enqueue_media();

		// Enqueue the WordPress color picker script.
		wp_enqueue_script( 'wp-color-picker' );

		// Enqueue the jQuery library.
		wp_enqueue_script( 'jquery' );

		// Conditionally enqueue the settings manager script for specific plugin admin pages.
		if ( 'toplevel_page_accessibility-onetap-settings' === $hook || 'onetap_page_accessibility-onetap-modules' === $hook ) {
			wp_enqueue_script( 'accessibility-onetap-settings-manager', ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/js/settings-manager.min.js', array( 'jquery' ), ACCESSIBILITY_ONETAP_VERSION, true );
		}
	}

	/**
	 * Set settings sections.
	 *
	 * @param array $sections setting sections array.
	 */
	public function set_sections( $sections ) {
		$this->settings_sections = $sections;

		return $this;
	}

	/**
	 * Add a single section.
	 *
	 * @param array $section The section details.
	 */
	public function add_section( $section ) {
		$this->settings_sections[] = $section;

		return $this;
	}

	/**
	 * Set settings fields.
	 *
	 * @param array $fields settings fields array.
	 */
	public function set_fields( $fields ) {
		$this->settings_fields = $fields;

		return $this;
	}

	/**
	 * Add settings fields.
	 *
	 * @param array $section settings section array.
	 * @param array $field settings fields array.
	 */
	public function add_field( $section, $field ) {
		$defaults = array(
			'name'  => '',
			'label' => '',
			'desc'  => '',
			'type'  => 'text',
		);

		$arg                                 = wp_parse_args( $field, $defaults );
		$this->settings_fields[ $section ][] = $arg;

		return $this;
	}

	/**
	 * Initialize and registers the settings sections and fileds to WordPress.
	 *
	 * Usually this should be called at `admin_init` hook.
	 *
	 * This function gets the initiated settings sections and fields. Then
	 * registers them to WordPress and ready for use.
	 */
	public function admin_init() {
		// register settings sections.
		foreach ( $this->settings_sections as $section ) {
			if ( false === get_option( $section['id'] ) ) {
				add_option( $section['id'] );
			}

			if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
				$section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';
				$callback        = function () use ( $section ) {
					echo esc_html( str_replace( '"', '\"', $section['desc'] ) );
				};
			} elseif ( isset( $section['callback'] ) ) {
				$callback = $section['callback'];
			} else {
				$callback = null;
			}

			add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
		}

		// register settings fields.
		foreach ( $this->settings_fields as $section => $field ) {
			foreach ( $field as $option ) {

				$name              = $option['name'];
				$type              = isset( $option['type'] ) ? $option['type'] : 'text';
				$label             = isset( $option['label'] ) ? $option['label'] : '';
				$group_title       = isset( $option['group_title'] ) ? $option['group_title'] : '';
				$group_description = isset( $option['group_description'] ) ? $option['group_description'] : '';
				$site_title        = isset( $option['site_title'] ) ? $option['site_title'] : '';
				$status            = isset( $option['status'] ) ? $option['status'] : '';
				$anchor            = isset( $option['anchor'] ) ? $option['anchor'] : '';
				$site_description  = isset( $option['site_description'] ) ? $option['site_description'] : '';
				$label_checkbox    = isset( $option['label_checkbox'] ) ? $option['label_checkbox'] : '';
				$icon              = isset( $option['icon'] ) ? $option['icon'] : '';
				$callback          = isset( $option['callback'] ) ? array( $this, $option['callback'] ) : array( $this, 'callback_' . $type );

				$args = array(
					'id'                => $name,
					'class'             => isset( $option['class'] ) ? $option['class'] : $name,
					'label_for'         => "{$section}[{$name}]",
					'group_title'       => $group_title,
					'group_description' => $group_description,
					'icon'              => $icon,
					'status'            => $status,
					'anchor'            => $anchor,
					'site_title'        => $site_title,
					'site_description'  => $site_description,
					'label_checkbox'    => $label_checkbox,
					'desc'              => isset( $option['desc'] ) ? $option['desc'] : '',
					'name'              => $label,
					'section'           => $section,
					'size'              => isset( $option['size'] ) ? $option['size'] : null,
					'options'           => isset( $option['options'] ) ? $option['options'] : '',
					'std'               => isset( $option['default'] ) ? $option['default'] : '',
					'sanitize_callback' => isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : '',
					'type'              => $type,
					'placeholder'       => isset( $option['placeholder'] ) ? $option['placeholder'] : '',
					'min'               => isset( $option['min'] ) ? $option['min'] : '',
					'max'               => isset( $option['max'] ) ? $option['max'] : '',
					'step'              => isset( $option['step'] ) ? $option['step'] : '',
				);

				add_settings_field( "{$section}[{$name}]", $label, $callback, $section, $section, $args );
			}
		}

		// creates our settings in the options table.
		foreach ( $this->settings_sections as $section ) {
			register_setting( $section['id'], $section['id'], array( $this, 'sanitize_options' ) );
		}
	}

	/**
	 * Get field description for display.
	 *
	 * @param array $args settings field args.
	 */
	public function get_field_description( $args ) {
		if ( ! empty( $args['desc'] ) ) {
			$desc = sprintf( '<p class="description">%s</p>', $args['desc'] );
		} else {
			$desc = '';
		}

		return $desc;
	}

	/**
	 * Displays a text field for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_text( $args ) {

		$value       = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size        = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
		$type        = isset( $args['type'] ) ? $args['type'] : 'text';
		$placeholder = empty( $args['placeholder'] ) ? '' : esc_attr( $args['placeholder'] );

		$html  = '<input type="' . esc_attr( $type ) . '" class="' . esc_attr( $size ) . '-text" id="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" value="' . esc_attr( $value ) . '" placeholder="' . $placeholder . '"/>';
		$html .= $this->get_field_description( $args );

		// DList of allowed HTML elements.
		$allowed_html = array(
			'input' => array(
				'type'        => array(),
				'class'       => array(),
				'id'          => array(),
				'name'        => array(),
				'value'       => array(),
				'placeholder' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a text field for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_template_license( $args ) {

		$value            = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size             = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
		$name             = isset( $args['name'] ) ? $args['name'] : '';
		$label_for        = isset( $args['label_for'] ) ? $args['label_for'] : '';
		$site_title       = isset( $args['site_title'] ) ? $args['site_title'] : '';
		$site_description = isset( $args['site_description'] ) ? $args['site_description'] : '';
		$type             = isset( $args['type'] ) ? $args['type'] : 'text';
		$placeholder      = empty( $args['placeholder'] ) ? '' : ' placeholder="' . esc_attr( $args['placeholder'] ) . '"';

		$html  = '<div class="box-setting-option ' . esc_attr( $args['id'] ) . '">';
		$html .= '<span id="anchorLicenseKey" class="site-title">' . esc_html( $site_title ) . '</span>';
		$html .= '<span class="site-description">' . esc_html( $site_description ) . '</span>';
		$html .= '<div class="box-control">';
		$html .= '<label class="label" id="' . esc_attr( $label_for ) . '">' . esc_html( $name ) . '</label>';
		$html .= '<input type="' . esc_attr( $type ) . '" class="' . esc_attr( $size ) . '-text" id="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" value="' . esc_attr( $value ) . '"' . $placeholder . '/>';
		$html .= $this->get_field_description( $args );
		$html .= '<button class="save-changes">' . esc_html__( 'Save Changes', 'accessibility-onetap' ) . '</button>';
		$html .= '</div>';
		$html .= '</div>';

		// List of allowed HTML elements.
		$allowed_html = array(
			'div'    => array(
				'class' => array(),
			),
			'span'   => array(
				'id'    => array(),
				'class' => array(),
			),
			'label'  => array(
				'class' => array(),
				'id'    => array(),
			),
			'input'  => array(
				'type'        => array(),
				'class'       => array(),
				'id'          => array(),
				'name'        => array(),
				'value'       => array(),
				'placeholder' => array(),
			),
			'button' => array(
				'class' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a url field for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_url( $args ) {
		$this->callback_text( $args );
	}

	/**
	 * Displays a number field for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_number( $args ) {
		$value       = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size        = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
		$type        = isset( $args['type'] ) ? $args['type'] : 'number';
		$placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . esc_attr( $args['placeholder'] ) . '"';
		$min         = ( '' === $args['min'] ) ? '' : ' min="' . esc_attr( $args['min'] ) . '"';
		$max         = ( '' === $args['max'] ) ? '' : ' max="' . esc_attr( $args['max'] ) . '"';
		$step        = ( '' === $args['step'] ) ? '' : ' step="' . esc_attr( $args['step'] ) . '"';

		$html  = '<input type="' . esc_attr( $type ) . '" class="' . esc_attr( $size ) . '-number" id="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" value="' . esc_attr( $value ) . '"' . $placeholder . $min . $max . $step . '/>';
		$html .= $this->get_field_description( $args );

		// List of allowed HTML elements.
		$allowed_html = array(
			'input' => array(
				'type'        => array(),
				'class'       => array(),
				'id'          => array(),
				'name'        => array(),
				'value'       => array(),
				'placeholder' => array(),
				'min'         => array(),
				'max'         => array(),
				'step'        => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a radio icons field for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_template_radio_icons( $args ) {
		$value            = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$site_title       = isset( $args['site_title'] ) ? $args['site_title'] : '';
		$site_description = isset( $args['site_description'] ) ? $args['site_description'] : '';
		$label_checkbox   = isset( $args['label_checkbox'] ) ? $args['label_checkbox'] : '';
		$type             = isset( $args['type'] ) ? $args['type'] : 'checkbox';

		$icons = array(
			'design1' => 'Original_Logo_Icon.svg',
			'design2' => 'Hand_Icon.svg',
			'design3' => 'Accessibility-Man-Icon.svg',
			'design4' => 'Settings-Filter-Icon.svg',
			'design5' => 'Switcher-Icon.svg',
			'design6' => 'Eye-Show-Icon.svg',
		);

		$html  = '<div class="box-setting-option ' . esc_attr( $args['id'] ) . '">';
		$html .= '<span id="anchorDesign" class="site-title">' . esc_html( $site_title ) . '</span>';
		$html .= '<span class="site-description">' . $site_description . '</span>';
		$html .= '<div class="box-control">';
		$html .= '<span class="title-label">' . esc_html( $label_checkbox ) . '</span>';
		$html .= '<div class="boxes">';
		// Loop for generata box.
		foreach ( $icons as $icon_value => $icon_image ) {
			$checked = ( isset( $value ) && $value === $icon_value ) ? 'checked' : '';

			$html .= '<div class="box">';
			$html .= '<label class="label ' . $checked . '">';
			$html .= '<img src="' . ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/' . esc_attr( $icon_image ) . '" />';
			$html .= '<input type="' . esc_attr( $type ) . '" 
						class="design-icons ' . $checked . '" 
						id="' . esc_attr( $args['id'] ) . esc_attr( str_replace( 'design-icon', '', $icon_value ) ) . '" 
						name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" 
						value="' . esc_attr( $icon_value ) . '" ' . $checked . ' />';
			$html .= '</label>';
			$html .= '</div>';
		}

		$html .= '</div>'; // .boxes.
		$html .= '</div>'; // .box-control.
		$html .= '</div>'; // .box-setting-option.

		// List of allowed HTML elements.
		$allowed_html = array(
			'div'    => array(
				'class' => array(),
			),
			'img'    => array(
				'class' => array(),
				'src'   => array(),
			),
			'span'   => array(
				'id'    => array(),
				'class' => array(),
			),
			'label'  => array(
				'class' => array(),
				'id'    => array(),
			),
			'input'  => array(
				'type'    => array(),
				'class'   => array(),
				'id'      => array(),
				'name'    => array(),
				'value'   => array(),
				'style'   => array(),
				'checked' => array(),
			),
			'button' => array(
				'class' => array(),
			),
			'a'      => array(
				'href'   => array(),
				'target' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a radio size field for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_template_radio_size( $args ) {
		$value            = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$site_title       = isset( $args['site_title'] ) ? $args['site_title'] : '';
		$site_description = isset( $args['site_description'] ) ? $args['site_description'] : '';
		$label_checkbox   = isset( $args['label_checkbox'] ) ? $args['label_checkbox'] : '';
		$type             = isset( $args['type'] ) ? $args['type'] : 'checkbox';

		$icons = array(
			'design-size1' => 'Original_Logo_Icon_Size1.svg',
			'design-size2' => 'Original_Logo_Icon_Size2.svg',
			'design-size3' => 'Original_Logo_Icon_Size3.svg',
		);

		$html  = '<div class="box-setting-option ' . esc_attr( $args['id'] ) . '">';
		$html .= '<span class="site-title">' . esc_html( $site_title ) . '</span>';
		$html .= '<span class="site-description">' . esc_html( $site_description ) . '</span>';
		$html .= '<div class="box-control">';
		$html .= '<span class="title-label">' . esc_html( $label_checkbox ) . '</span>';
		$html .= '<div class="boxes">';
		// Loop for generata box.
		foreach ( $icons as $icon_value => $icon_image ) {
			$checked = ( isset( $value ) && $value === $icon_value ) ? 'checked' : '';

			$html .= '<div class="box">';
			$html .= '<label class="label ' . $checked . '">';
			$html .= '<img src="' . ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/' . esc_attr( $icon_image ) . '" />';
			$html .= '<input type="' . esc_attr( $type ) . '" 
						class="design-icons ' . $checked . '" 
						id="' . esc_attr( $args['id'] ) . esc_attr( str_replace( 'design-icon', '', $icon_value ) ) . '" 
						name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" 
						value="' . esc_attr( $icon_value ) . '" ' . $checked . ' />';
			$html .= '</label>';
			$html .= '</div>';
		}

		$html .= '</div>'; // .boxes.
		$html .= '</div>'; // .box-control.
		$html .= '</div>'; // .box-setting-option.

		// List of allowed HTML elements.
		$allowed_html = array(
			'div'    => array(
				'class' => array(),
			),
			'img'    => array(
				'class' => array(),
				'src'   => array(),
			),
			'span'   => array(
				'class' => array(),
			),
			'label'  => array(
				'class' => array(),
				'id'    => array(),
			),
			'input'  => array(
				'type'    => array(),
				'class'   => array(),
				'id'      => array(),
				'name'    => array(),
				'value'   => array(),
				'style'   => array(),
				'checked' => array(),
			),
			'button' => array(
				'class' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a radio border field for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_template_radio_border( $args ) {
		$value            = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$site_title       = isset( $args['site_title'] ) ? $args['site_title'] : '';
		$site_description = isset( $args['site_description'] ) ? $args['site_description'] : '';
		$label_checkbox   = isset( $args['label_checkbox'] ) ? $args['label_checkbox'] : '';
		$type             = isset( $args['type'] ) ? $args['type'] : 'checkbox';

		$icons = array(
			'design-border1' => 'Original_Logo_Icon.svg',
			'design-border2' => 'Original_Logo_Icon.svg',
		);

		$html  = '<div class="box-setting-option ' . esc_attr( $args['id'] ) . '">';
		$html .= '<span class="site-title">' . esc_html( $site_title ) . '</span>';
		$html .= '<span class="site-description">' . esc_html( $site_description ) . '</span>';
		$html .= '<div class="box-control">';
		$html .= '<span class="title-label">' . esc_html( $label_checkbox ) . '</span>';
		$html .= '<div class="boxes">';
		// Loop for generata box.
		foreach ( $icons as $icon_value => $icon_image ) {
			$checked = ( isset( $value ) && $value === $icon_value ) ? 'checked' : '';

			$html .= '<div class="box">';
			$html .= '<label class="label ' . $checked . '">';
			$html .= '<img src="' . ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/' . esc_attr( $icon_image ) . '" />';
			$html .= '<input type="' . esc_attr( $type ) . '" 
						class="design-icons ' . $checked . '" 
						id="' . esc_attr( $args['id'] ) . esc_attr( str_replace( 'design-icon', '', $icon_value ) ) . '" 
						name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" 
						value="' . esc_attr( $icon_value ) . '" ' . $checked . ' />';
			$html .= '</label>';
			$html .= '</div>';
		}

		$html .= '</div>'; // .boxes.
		$html .= '<button class="save-changes">' . esc_html__( 'Save Changes', 'accessibility-onetap' ) . '</button>';
		$html .= '</div>'; // .box-control.
		$html .= '</div>'; // .box-setting-option.

		// List of allowed HTML elements.
		$allowed_html = array(
			'div'    => array(
				'class' => array(),
			),
			'img'    => array(
				'class' => array(),
				'src'   => array(),
			),
			'span'   => array(
				'class' => array(),
			),
			'label'  => array(
				'class' => array(),
				'id'    => array(),
			),
			'input'  => array(
				'type'    => array(),
				'class'   => array(),
				'id'      => array(),
				'name'    => array(),
				'value'   => array(),
				'style'   => array(),
				'checked' => array(),
			),
			'button' => array(
				'class' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a number field for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_template_number_top_bottom( $args ) {
		$value            = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size             = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
		$name             = isset( $args['name'] ) ? $args['name'] : '';
		$label_for        = isset( $args['label_for'] ) ? $args['label_for'] : '';
		$site_title       = isset( $args['site_title'] ) ? $args['site_title'] : '';
		$site_description = isset( $args['site_description'] ) ? $args['site_description'] : '';
		$type             = isset( $args['type'] ) ? $args['type'] : 'number';
		$placeholder      = empty( $args['placeholder'] ) ? '' : ' placeholder="' . esc_attr( $args['placeholder'] ) . '"';
		$min              = ( '' === $args['min'] ) ? '' : ' min="' . esc_attr( $args['min'] ) . '"';
		$max              = ( '' === $args['max'] ) ? '' : ' max="' . esc_attr( $args['max'] ) . '"';
		$step             = ( '' === $args['step'] ) ? '' : ' step="' . esc_attr( $args['step'] ) . '"';

		$html  = '<div class="box-setting-option ' . esc_attr( $args['id'] ) . '">';
		$html .= '<span id="anchorPosition" class="site-title">' . esc_html( $site_title ) . '</span>';
		$html .= '<span class="site-description">' . $site_description . '</span>';
		$html .= '<div class="box-device">';
		$html .= '<div class="boxes">';
		$html .= '<button type="button" class="desktop active">';
		$html .= '<img src="' . ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/Device_Desktop.svg" />';
		$html .= '<span>';
		$html .= esc_html__( 'Desktop', 'accessibility-onetap' );
		$html .= '</span>';
		$html .= '</button>';
		$html .= '<button type="button" class="tablet">';
		$html .= '<img src="' . ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/Device_Tablet.svg" />';
		$html .= '<span>';
		$html .= esc_html__( 'Tablet', 'accessibility-onetap' );
		$html .= '</span>';
		$html .= '</button>';
		$html .= '<button type="button" class="mobile">';
		$html .= '<img src="' . ACCESSIBILITY_ONETAP_PLUGINS_URL . 'assets/images/admin/Device_Mobile.svg" />';
		$html .= '<span>';
		$html .= esc_html__( 'Mobile', 'accessibility-onetap' );
		$html .= '</span>';
		$html .= '</button>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '<div class="box-control">';
		$html .= '<label class="label" id="' . esc_attr( $label_for ) . '">' . esc_html( $name ) . '</label>';
		$html .= '<input type="' . esc_attr( $type ) . '" class="' . esc_attr( $size ) . '-number" id="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" value="' . esc_attr( $value ) . '"' . $placeholder . $min . $max . $step . '/>';
		$html .= $this->get_field_description( $args );
		$html .= '<button class="save-changes">' . esc_html__( 'Save Changes', 'accessibility-onetap' ) . '</button>';
		$html .= '</div>';
		$html .= '</div>';

		// List of allowed HTML elements.
		$allowed_html = array(
			'div'    => array(
				'class' => array(),
			),
			'img'    => array(
				'src'   => array(),
				'id'    => array(),
				'class' => array(),
			),
			'span'   => array(
				'id'    => array(),
				'class' => array(),
			),
			'label'  => array(
				'class' => array(),
				'id'    => array(),
			),
			'input'  => array(
				'type'        => array(),
				'class'       => array(),
				'id'          => array(),
				'name'        => array(),
				'value'       => array(),
				'placeholder' => array(),
				'min'         => array(),
				'max'         => array(),
				'step'        => array(),
			),
			'button' => array(
				'type'  => array(),
				'class' => array(),
			),
			'a'      => array(
				'href'   => array(),
				'target' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a number field for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_template_number_left_right( $args ) {
		$value            = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size             = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
		$name             = isset( $args['name'] ) ? $args['name'] : '';
		$label_for        = isset( $args['label_for'] ) ? $args['label_for'] : '';
		$site_title       = isset( $args['site_title'] ) ? $args['site_title'] : '';
		$site_description = isset( $args['site_description'] ) ? $args['site_description'] : '';
		$type             = isset( $args['type'] ) ? $args['type'] : 'number';
		$placeholder      = empty( $args['placeholder'] ) ? '' : ' placeholder="' . esc_attr( $args['placeholder'] ) . '"';
		$min              = ( '' === $args['min'] ) ? '' : ' min="' . esc_attr( $args['min'] ) . '"';
		$max              = ( '' === $args['max'] ) ? '' : ' max="' . esc_attr( $args['max'] ) . '"';
		$step             = ( '' === $args['step'] ) ? '' : ' step="' . esc_attr( $args['step'] ) . '"';

		$html  = '<div class="box-setting-option ' . esc_attr( $args['id'] ) . '">';
		$html .= '<span class="site-title">' . esc_html( $site_title ) . '</span>';
		$html .= '<span class="site-description">' . esc_html( $site_description ) . '</span>';
		$html .= '<div class="box-control">';
		$html .= '<label class="label" id="' . esc_attr( $label_for ) . '">' . esc_html( $name ) . '</label>';
		$html .= '<input type="' . esc_attr( $type ) . '" class="' . esc_attr( $size ) . '-number" id="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" value="' . esc_attr( $value ) . '"' . $placeholder . $min . $max . $step . '/>';
		$html .= $this->get_field_description( $args );
		$html .= '<button class="save-changes">' . esc_html__( 'Save Changes', 'accessibility-onetap' ) . '</button>';
		$html .= '</div>';
		$html .= '</div>';

		// List of allowed HTML elements.
		$allowed_html = array(
			'div'    => array(
				'class' => array(),
			),
			'span'   => array(
				'class' => array(),
			),
			'label'  => array(
				'class' => array(),
				'id'    => array(),
			),
			'input'  => array(
				'type'        => array(),
				'class'       => array(),
				'id'          => array(),
				'name'        => array(),
				'value'       => array(),
				'placeholder' => array(),
				'min'         => array(),
				'max'         => array(),
				'step'        => array(),
			),
			'button' => array(
				'class' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a checkbox for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_checkbox( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );

		$html  = '<fieldset>';
		$html .= '<label for="wpuf-' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']">';
		$html .= '<input type="hidden" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" value="off" />';
		$html .= '<input type="checkbox" class="checkbox" id="wpuf-' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" value="on" ' . checked( $value, 'on', false ) . ' />';
		$html .= esc_html( $args['desc'] ) . '</label>';
		$html .= '</fieldset>';

		// List of allowed HTML elements.
		$allowed_html = array(
			'fieldset' => array(),
			'label'    => array(
				'for' => array(),
			),
			'input'    => array(
				'type'  => array(),
				'class' => array(),
				'id'    => array(),
				'name'  => array(),
				'value' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a multicheckbox for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_multicheck( $args ) {
		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		$html  = '<fieldset>';
		$html .= '<input type="hidden" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" value="" />';

		foreach ( $args['options'] as $key => $label ) {
			$checked = isset( $value[ $key ] ) ? $value[ $key ] : '0';
			$html   .= '<label for="wpuf-' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . '][' . esc_attr( $key ) . ']">';
			$html   .= '<input type="checkbox" class="checkbox" id="wpuf-' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . '][' . esc_attr( $key ) . ']" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . '][' . esc_attr( $key ) . ']" value="' . esc_attr( $key ) . '" ' . checked( $checked, $key, false ) . ' />';
			$html   .= esc_html( $label ) . '</label><br>';
		}

		$html .= $this->get_field_description( $args );
		$html .= '</fieldset>';

		// List of allowed HTML elements.
		$allowed_html = array(
			'fieldset' => array(),
			'label'    => array(
				'for' => array(),
			),
			'input'    => array(
				'type'  => array(),
				'class' => array(),
				'id'    => array(),
				'name'  => array(),
				'value' => array(),
			),
			'br'       => array(),
		);

		echo wp_kses( $html, $allowed_html );
	}
	/**
	 * Displays a radio button for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_radio( $args ) {
		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		$html  = '<fieldset>';

		foreach ( $args['options'] as $key => $label ) {
			$html .= '<label for="wpuf-' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . '][' . esc_attr( $key ) . ']">';
			$html .= '<input type="radio" class="radio" id="wpuf-' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . '][' . esc_attr( $key ) . ']" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" value="' . esc_attr( $key ) . '" ' . checked( $value, $key, false ) . ' />';
			$html .= esc_html( $label ) . '</label><br>';
		}

		$html .= $this->get_field_description( $args );
		$html .= '</fieldset>';

		// List of allowed HTML elements.
		$allowed_html = array(
			'fieldset' => array(),
			'label'    => array(
				'for' => array(),
			),
			'input'    => array(
				'type'  => array(),
				'class' => array(),
				'id'    => array(),
				'name'  => array(),
				'value' => array(),
			),
			'br'       => array(),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a selectbox for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_select( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? esc_attr( $args['size'] ) : 'regular';

		$html = '<select class="' . $size . '" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" id="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']">';

		foreach ( $args['options'] as $key => $label ) {
			$html .= '<option value="' . esc_attr( $key ) . '"' . selected( $value, $key, false ) . '>' . esc_html( $label ) . '</option>';
		}

		$html .= '</select>';
		$html .= $this->get_field_description( $args );

		// List of allowed HTML elements.
		$allowed_html = array(
			'select' => array(
				'class' => array(),
				'name'  => array(),
				'id'    => array(),
			),
			'option' => array(
				'value'    => array(),
				'selected' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a selectbox for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_template_language( $args ) {

		$value            = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size             = isset( $args['size'] ) && ! is_null( $args['size'] ) ? esc_attr( $args['size'] ) : 'regular';
		$name             = isset( $args['name'] ) ? esc_html( $args['name'] ) : '';
		$label_for        = isset( $args['label_for'] ) ? esc_attr( $args['label_for'] ) : '';
		$site_title       = isset( $args['site_title'] ) ? esc_html( $args['site_title'] ) : '';
		$site_description = isset( $args['site_description'] ) ? $args['site_description'] : '';

		$html  = '<div class="box-setting-option ' . esc_attr( $args['id'] ) . '">';
		$html .= '<span id="anchorLanguage" class="site-title">' . $site_title . '</span>';
		$html .= '<span class="site-description">' . $site_description . '</span>';
		$html .= '<div class="box-control">';
		$html .= '<label class="label" id="' . $label_for . '">' . $name . '</label>';
		$html .= '<select class="' . $size . '" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" id="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']">';

		foreach ( $args['options'] as $key => $label ) {
			$html .= '<option value="' . esc_attr( $key ) . '"' . selected( $value, $key, false ) . '>' . esc_html( $label ) . '</option>';
		}

		$html .= '</select>';
		$html .= $this->get_field_description( $args );
		$html .= '<button class="save-changes">' . esc_html__( 'Save Changes', 'accessibility-onetap' ) . '</button>';
		$html .= '</div>';
		$html .= '</div>';

		// List of allowed HTML elements.
		$allowed_html = array(
			'div'    => array(
				'class' => array(),
				'id'    => array(),
			),
			'span'   => array(
				'class' => array(),
				'id'    => array(),
			),
			'label'  => array(
				'class' => array(),
				'id'    => array(),
			),
			'select' => array(
				'class' => array(),
				'name'  => array(),
				'id'    => array(),
			),
			'option' => array(
				'value'    => array(),
				'selected' => array(),
			),
			'button' => array(
				'class' => array(),
			),
			'a'      => array(
				'href'   => array(),
				'target' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a selectbox for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_template_widget_position( $args ) {

		$value            = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size             = isset( $args['size'] ) && ! is_null( $args['size'] ) ? esc_attr( $args['size'] ) : 'regular';
		$name             = isset( $args['name'] ) ? esc_html( $args['name'] ) : '';
		$label_for        = isset( $args['label_for'] ) ? esc_attr( $args['label_for'] ) : '';
		$site_title       = isset( $args['site_title'] ) ? esc_html( $args['site_title'] ) : '';
		$site_description = isset( $args['site_description'] ) ? esc_html( $args['site_description'] ) : '';

		$html  = '<div class="box-setting-option ' . esc_attr( $args['id'] ) . '">';
		$html .= '<span class="site-title">' . $site_title . '</span>';
		$html .= '<span class="site-description">' . $site_description . '</span>';
		$html .= '<div class="box-control">';
		$html .= '<label class="label" id="' . $label_for . '">' . $name . '</label>';
		$html .= '<select class="' . $size . '" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" id="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']">';

		foreach ( $args['options'] as $key => $label ) {
			$html .= '<option value="' . esc_attr( $key ) . '"' . selected( $value, $key, false ) . '>' . esc_html( $label ) . '</option>';
		}

		$html .= '</select>';
		$html .= $this->get_field_description( $args );
		$html .= '<button class="save-changes">' . esc_html__( 'Save Changes', 'accessibility-onetap' ) . '</button>';
		$html .= '</div>';
		$html .= '</div>';

		// List of allowed HTML elements.
		$allowed_html = array(
			'div'    => array(
				'class' => array(),
				'id'    => array(),
			),
			'span'   => array(
				'class' => array(),
			),
			'label'  => array(
				'class' => array(),
				'id'    => array(),
			),
			'select' => array(
				'class' => array(),
				'name'  => array(),
				'id'    => array(),
			),
			'option' => array(
				'value'    => array(),
				'selected' => array(),
			),
			'button' => array(
				'class' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a textarea for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_textarea( $args ) {

		$value       = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size        = isset( $args['size'] ) && ! is_null( $args['size'] ) ? esc_attr( $args['size'] ) : 'regular';
		$placeholder = empty( $args['placeholder'] ) ? '' : ' placeholder="' . esc_attr( $args['placeholder'] ) . '"';

		$html  = '<textarea rows="5" cols="55" class="' . esc_attr( $size ) . '-text" id="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']"' . $placeholder . '>';
		$html .= $value;
		$html .= '</textarea>';
		$html .= $this->get_field_description( $args );

		// List of allowed HTML elements.
		$allowed_html = array(
			'textarea' => array(
				'rows'        => array(),
				'cols'        => array(),
				'class'       => array(),
				'id'          => array(),
				'name'        => array(),
				'placeholder' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays the html for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_html( $args ) {
		$html = $this->get_field_description( $args );
		echo wp_kses_post( $html );
	}

	/**
	 * Displays a rich text textarea for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_wysiwyg( $args ) {
		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : '500px';

		$html = '<div style="max-width: ' . esc_attr( $size ) . ';">';

		$editor_settings = array(
			'teeny'         => true,
			'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
			'textarea_rows' => 10,
		);

		if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
			$editor_settings = array_merge( $editor_settings, $args['options'] );
		}

		$html .= wp_editor( $value, $args['section'] . '-' . $args['id'], $editor_settings );

		$html .= '</div>';

		$html .= wp_kses_post( $this->get_field_description( $args ) );

		return $html;
	}

	/**
	 * Displays a file upload field for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_file( $args ) {

		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? esc_attr( $args['size'] ) : 'regular';
		$id    = esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']';
		$label = isset( $args['options']['button_label'] ) ? esc_html( $args['options']['button_label'] ) : esc_html__( 'Choose File', 'accessibility-onetap' );

		$html  = '<input type="text" class="' . $size . '-text setting-manager-url" id="' . $id . '" name="' . $id . '" value="' . $value . '"/>';
		$html .= '<input type="button" class="button setting-manager-media-browse" value="' . $label . '" />';
		$html .= $this->get_field_description( $args );

		// List of allowed HTML elements.
		$allowed_html = array(
			'input'  => array(
				'type'  => array(),
				'class' => array(),
				'id'    => array(),
				'name'  => array(),
				'value' => array(),
			),
			'button' => array(
				'class' => array(),
				'value' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a password field for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_password( $args ) {

		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? esc_attr( $args['size'] ) : 'regular';
		$id    = esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']';

		$html  = '<input type="password" class="' . $size . '-text" id="' . $id . '" name="' . $id . '" value="' . $value . '"/>';
		$html .= $this->get_field_description( $args );

		// List of allowed HTML elements.
		$allowed_html = array(
			'input' => array(
				'type'  => array(),
				'class' => array(),
				'id'    => array(),
				'name'  => array(),
				'value' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a color picker field for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_color( $args ) {

		$value         = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size          = isset( $args['size'] ) && ! is_null( $args['size'] ) ? esc_attr( $args['size'] ) : 'regular';
		$id            = esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']';
		$default_color = esc_attr( $args['std'] );

		$html  = '<input type="text" class="' . $size . '-text wp-color-picker-field" id="' . $id . '" name="' . $id . '" value="' . $value . '" data-default-color="' . $default_color . '" />';
		$html .= $this->get_field_description( $args );

		// List of allowed HTML elements.
		$allowed_html = array(
			'input' => array(
				'type'               => array(),
				'class'              => array(),
				'id'                 => array(),
				'name'               => array(),
				'value'              => array(),
				'data-default-color' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a color picker field for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_template_color( $args ) {

		$value            = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size             = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
		$name             = isset( $args['name'] ) ? $args['name'] : '';
		$label_for        = isset( $args['label_for'] ) ? $args['label_for'] : '';
		$site_title       = isset( $args['site_title'] ) ? $args['site_title'] : '';
		$site_description = isset( $args['site_description'] ) ? $args['site_description'] : '';

		$html  = '<div class="box-setting-option ' . esc_attr( $args['id'] ) . '">';
		$html .= '<span id="anchorColors" class="site-title">' . esc_html( $site_title ) . '</span>';
		$html .= '<span class="site-description">' . $site_description . '</span>';
		$html .= '<div class="box-control">';
		$html .= '<label class="label" id="' . esc_attr( $label_for ) . '">' . esc_html( $name ) . '</label>';
		$html .= '<input type="text" class="' . esc_attr( $size ) . '-text wp-color-picker-field" id="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" value="' . esc_attr( $value ) . '" data-default-color="' . esc_attr( $args['std'] ) . '" />';
		$html .= $this->get_field_description( $args );
		$html .= '<button class="save-changes">' . esc_html__( 'Save Changes', 'accessibility-onetap' ) . '</button>';
		$html .= '</div>';
		$html .= '</div>';

		// List of allowed HTML elements.
		$allowed_html = array(
			'div'    => array(
				'class' => array(),
			),
			'span'   => array(
				'id'    => array(),
				'class' => array(),
			),
			'label'  => array(
				'class' => array(),
				'id'    => array(),
			),
			'input'  => array(
				'type'               => array(),
				'class'              => array(),
				'id'                 => array(),
				'name'               => array(),
				'value'              => array(),
				'data-default-color' => array(),
			),
			'button' => array(
				'class' => array(),
			),
			'a'      => array(
				'href'   => array(),
				'target' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays title;
	 *
	 * @param array $args settings field args.
	 */
	public function callback_template_general_module_title( $args ) {
		$group_title       = isset( $args['group_title'] ) ? $args['group_title'] : '';
		$group_description = isset( $args['group_description'] ) ? $args['group_description'] : '';
		$anchor            = isset( $args['anchor'] ) ? $args['anchor'] : '';

		$html  = '<div class="box-site-info">';
		$html .= '<span id="' . esc_attr( $anchor ) . '" class="group-title">' . esc_html( $group_title ) . '</span>';
		$html .= '<span class="group-description">' . esc_html( $group_description ) . '</span>';
		$html .= '</div>';

		// List of allowed HTML elements.
		$allowed_html = array(
			'div'  => array(
				'class' => array(),
			),
			'span' => array(
				'id'    => array(),
				'class' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Display save changes.
	 */
	public function callback_template_save_changes() {
		$html = '<button class="save-changes">' . esc_html__( 'Save Changes', 'accessibility-onetap' ) . '</button>';

		// List of allowed HTML elements.
		$allowed_html = array(
			'button' => array(
				'class' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a checkbox for a settings field.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_template_checkbox( $args ) {

		$value  = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$status = $args['status'] ? ' onetap-pro' : ' onetap-free';
		$html   = '<div class="box-custom-checkbox' . esc_attr( $status ) . '">';
		$html  .= '<div class="left">';
		$html  .= '<div class="icon">';
		$html  .= '<img src="' . esc_url( $args['icon'] ) . '" alt="icon">';
		$html  .= '</div>';
		$html  .= '<div class="text">';
		if ( $args['status'] ) {
			$value = false;
			$html .= '<span class="status-pro">' . esc_html__( 'PRO', 'accessibility-onetap' ) . '</span>';
		}
		$html .= '<span class="title">' . esc_html( $args['name'] ) . '</span>';
		$html .= '<span class="desc">' . esc_html( $args['desc'] ) . '</span>';
		$html .= '</div>';
		$html .= '</div>';
		$html .= '<div class="right">';
		$html .= '<label class="switch" for="wpuf-' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']">';
		$html .= '<input type="hidden" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" value="off" />';
		$html .= '<input type="checkbox" class="checkbox" id="wpuf-' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" name="' . esc_attr( $args['section'] ) . '[' . esc_attr( $args['id'] ) . ']" value="on" ' . checked( $value, 'on', false ) . ' />';
		$html .= '<span class="slider round"></span>';
		$html .= '</label>';
		$html .= '</div>';
		$html .= '</div>';

		// List of allowed HTML elements.
		$allowed_html = array(
			'div'   => array( 'class' => array() ),
			'img'   => array(
				'src' => array(),
				'alt' => array(),
			),
			'span'  => array( 'class' => array() ),
			'label' => array(
				'class' => array(),
				'for'   => array(),
			),
			'input' => array(
				'type'    => array(),
				'class'   => array(),
				'id'      => array(),
				'name'    => array(),
				'value'   => array(),
				'checked' => array(),
			),
		);

		echo wp_kses( $html, $allowed_html );
	}

	/**
	 * Displays a select box for creating the pages select box.
	 *
	 * @param array $args settings field args.
	 */
	public function callback_pages( $args ) {

		// Generate the dropdown HTML.
		$html = wp_dropdown_pages(
			array(
				'selected' => esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) ),
				'name'     => esc_attr( $args['section'] . '[' . $args['id'] . ']' ),
				'id'       => esc_attr( $args['section'] . '[' . $args['id'] . ']' ),
				'echo'     => 0,
			)
		);

		// Escape output for safety.
		echo wp_kses(
			$html,
			array(
				'select' => array(
					'name' => array(),
					'id'   => array(),
				),
				'option' => array(
					'value' => array(),
				),
			)
		);
	}

	/**
	 * Sanitize callback for Settings API.
	 *
	 * @param object $options sanitize options.
	 */
	public function sanitize_options( $options ) {

		if ( ! $options ) {
			return $options;
		}

		foreach ( $options as $option_slug => $option_value ) {
			$sanitize_callback = $this->get_sanitize_callback( $option_slug );

			// If callback is set, call it.
			if ( $sanitize_callback ) {
				$options[ $option_slug ] = call_user_func( $sanitize_callback, $option_value );
				continue;
			}
		}

		return $options;
	}

	/**
	 * Get sanitization callback for given option slug.
	 *
	 * @param string $slug option slug.
	 *
	 * @return mixed string or bool false.
	 */
	public function get_sanitize_callback( $slug = '' ) {
		if ( empty( $slug ) ) {
			return false;
		}

		// Iterate over registered fields and see if we can find proper callback.
		foreach ( $this->settings_fields as $section => $options ) {
			foreach ( $options as $option ) {
				if ( $option['name'] !== $slug ) {
					continue;
				}

				// Return the callback name.
				return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
			}
		}

		return false;
	}

	/**
	 * Get the value of a settings field.
	 *
	 * @param string $option  settings field name.
	 * @param string $section the section name this field belongs to.
	 * @param string $default_value default value text if it's not found.
	 * @return string
	 */
	public function get_option( $option, $section, $default_value = '' ) {

		$options = get_option( $section );

		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ];
		}

		return $default_value;
	}

	/**
	 * Show navigations as tab.
	 *
	 * Shows all the settings section labels as tab.
	 */
	public function show_navigation() {
		$html = '<h2 class="nav-tab-wrapper">';

		$count = count( $this->settings_sections );

		// Don't show the navigation if only one section exists.
		if ( 1 === $count ) {
			return;
		}

		foreach ( $this->settings_sections as $tab ) {
			$html .= '<a href="' . esc_url( '#' . $tab['id'] ) . '" class="nav-tab" id="' . esc_attr( $tab['id'] ) . '-tab">' . esc_html( $tab['title'] ) . '</a>';
		}

		$html .= '</h2>';

		echo wp_kses(
			$html,
			array(
				'h2' => array(),
				'a'  => array(
					'href'  => array(),
					'class' => array(),
					'id'    => array(),
				),
			)
		);
	}

	/**
	 * Show the section settings forms.
	 *
	 * This function displays every sections in a different form.
	 */
	public function show_forms() {
		?>
		<div class="metabox-holder">
			<?php foreach ( $this->settings_sections as $form ) { ?>
				<div id="<?php echo esc_attr( $form['id'] ); ?>" class="group" style="display: none;">
					<form method="post" action="options.php">
						<?php
						do_action( 'accessibility_onetap_settings_manager_form_top_' . $form['id'], $form );
						settings_fields( $form['id'] );
						do_settings_sections( $form['id'] );
						do_action( 'accessibility_onetap_settings_manager_form_bottom_' . $form['id'], $form );
						if ( isset( $this->settings_fields[ $form['id'] ] ) ) :
							?>
						<div style="padding-left: 10px; display: none;">
							<?php submit_button(); ?>
						</div>
						<?php endif; ?>
					</form>
				</div>
			<?php } ?>
		</div>
		<?php
	}
}
