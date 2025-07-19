<?php

/**
 * Class description
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Tricks_Elementor_Section_Extension' ) ) {

	/**
	 * Define Jet_Tricks_Elementor_Section_Extension class
	 */
	class Jet_Tricks_Elementor_Section_Extension {

		/**
		 * Sections Data
		 *
		 * @var array
		 */
		public $sections_data = array();

		/**
		 * [$view_more_sections description]
		 *
		 * @var array
		 */
		public $view_more_sections = array();

		/**
		 * [$particle_sections description]
		 * @var array
		 */
		public $particle_sections = array();

		/**
		 * [$avaliable_extensions description]
		 * @var array
		 */
		public $avaliable_extensions = array();

		/**
		 * [$default_section_settings description]
		 * @var array
		 */
		public $default_section_settings = array(
			'section_view_more'                 => false,
			'section_jet_tricks_particles'      => false,
			'section_jet_tricks_particles_json' => '',
		);

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    object
		 */
		private static $instance = null;

		/**
		 * Init Handler
		 */
		public function init() {
			$this->avaliable_extensions = jet_tricks_settings()->get( 'avaliable_extensions', jet_tricks_settings()->default_avaliable_extensions );

			if ( ! filter_var( $this->avaliable_extensions['section_particles'], FILTER_VALIDATE_BOOLEAN ) &&
				 ! filter_var( $this->avaliable_extensions['column_sticky'], FILTER_VALIDATE_BOOLEAN ) 
			) {
				return false;
			}

			add_action( 'elementor/frontend/builder_content_data', array( $this, 'get_view_more_sections' ) );

			add_action( 'elementor/element/section/section_advanced/after_section_end', array( $this, 'after_section_section_advanced' ), 10, 2 );

			add_action( 'elementor/element/container/section_layout/after_section_end', array( $this, 'after_section_section_advanced' ), 10, 2 );

			add_action( 'elementor/frontend/section/before_render', array( $this, 'section_before_render' ) );

			add_action( 'elementor/frontend/container/before_render', array( $this, 'section_before_render' ) );

			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );

			add_action( 'elementor/preview/enqueue_scripts', array( $this, 'enqueue_preview_scripts' ), 9 );
		}

/**
		 * [after_section_section_advanced description]
		 * @param  [type] $obj  [description]
		 * @param  [type] $args [description]
		 * @return [type]       [description]
		 */
		public function after_section_section_advanced( $obj, $args ) {

			$obj->start_controls_section(
				'section_jet_tricks_settings',
				array(
					'label' => esc_html__( 'JetTricks', 'jet-tricks' ),
					'tab'   => Elementor\Controls_Manager::TAB_ADVANCED,
				)
			);

			$this->section_jet_tricks_particles_json_settings( $obj );

			$obj->end_controls_section();
		}

		/**
		 * [register_particles_json_settings description]
		 * @param  [type] $obj [description]
		 * @return [type]      [description]
		 */
		public function section_jet_tricks_particles_json_settings( $obj ) {

			if ( ! filter_var( $this->avaliable_extensions['section_particles'], FILTER_VALIDATE_BOOLEAN ) ) {
				return false;
			}

			$particles_version = jet_tricks_settings()->get( 'particles_version', '1.18.11' );

			$generator_url = $particles_version === '3.0.2'
				? 'https://particles.js.org/'
				: 'https://vincentgarreau.com/particles.js/';

			$description = sprintf(
				__( 'Paste your particles JSON code here - Generate it from <a href="%s" target="_blank">Here!</a>', 'jet-tricks' ),
				esc_url( $generator_url )
			);

			if ( $particles_version === '1.18.11' ) {
				$description .= ' ' . sprintf(
						__( 'You can also <a href="%s">switch to the new Particles version</a>.', 'jet-tricks' ),
						esc_url( admin_url( 'admin.php?page=jet-dashboard-settings-page&subpage=jet-tricks-general-settings' ) )
					);
			} elseif ( $particles_version === '3.0.2' ) {
				$description .= ' ' . __( 'Version 3.0.2 enables full-screen animation by default. To disable it, set "fullScreen: true" to false in your JSON.', 'jet-tricks' );
			}

			$obj->add_control(
				'section_jet_tricks_particles',
				array(
					'label'        => esc_html__( 'Enable Particles', 'jet-tricks' ),
					'type'         => Elementor\Controls_Manager::SWITCHER,
					'default'      => 'false',
					'label_on'     => 'Yes',
					'label_off'    => 'No',
					'return_value' => 'true',
					'description'  => esc_html__( 'Switch on to enable & access Particles options!', 'jet-tricks' ),
					'render_type'  => 'template',
				)
			);

			$obj->add_control(
				'section_jet_tricks_particles_json',
				array(
					'label'       => esc_html__( 'Particles JSON', 'jet-tricks' ),
					'type'        => Elementor\Controls_Manager::TEXTAREA,
					'description' => $description,
					'default'     => '',
					'render_type' => 'template',
					'dynamic'     => array( 'active' => true ),
					'condition'   => array(
						'section_jet_tricks_particles' => 'true',
					),
				)
			);

		}

		/**
		 * [section_before_render description]
		 * @param  [type] $element [description]
		 * @return [type]          [description]
		 */
		public function section_before_render( $element ) {
			$data            = $element->get_data();
			$type            = isset( $data['elType'] ) ? $data['elType'] : 'section';
			$elementSettings = $element->get_settings_for_display();

			if ( 'section' !== $type && 'container' !== $type ) {
				return false;
			}

			$settings    = $data['settings'];
			$section_id  = $data['id'];

			$settings = wp_parse_args( $settings, $this->default_section_settings );

			if ( ! empty( $settings['_element_id'] ) && in_array( $settings['_element_id'], $this->view_more_sections ) ) {
				$element->add_render_attribute( '_wrapper', array(
					'class' => 'jet-view-more-section',
				) );
			}

			if ( filter_var( $settings['section_jet_tricks_particles'], FILTER_VALIDATE_BOOLEAN ) ) {
				$element->add_render_attribute( '_wrapper', array(
					'class' => 'jet-tricks-particles-section',
				) );

				$this->particle_sections[] = $data['id'];
			}

			$section_settings = array(
				'view_more'      => filter_var( $settings['section_view_more'], FILTER_VALIDATE_BOOLEAN ),
				'particles'      => filter_var( $settings['section_jet_tricks_particles'], FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false',
				'particles_json' => isset( $elementSettings['section_jet_tricks_particles_json'] ) ? $elementSettings['section_jet_tricks_particles_json'] : '',
			);

			$this->sections_data[ $data['id'] ] = $section_settings;
		}

		/**
		 * [get_view_more_sections description]
		 * @param  [type] $data [description]
		 * @return [type]       [description]
		 */
		public function get_view_more_sections( $data ) {
			$sections = array();

			foreach ( $data as $key => $section_data ) {
				if ( ! empty( $section_data['elements'] ) ) {
					foreach ( $section_data['elements'] as $key => $column_data ) {
						if ( ! empty( $column_data['elements'] ) ) {
							foreach ( $column_data['elements'] as $key => $widget_data ) {
								if ( 'widget' === $widget_data['elType'] && 'jet-view-more' === $widget_data['widgetType'] ) {
									foreach ( $widget_data['settings']['sections'] as $key => $section ) {
										$sections[] = $section['section_id'];
									}
								}
							}
						}
					}
				}
			}

			$this->view_more_sections = array_unique( $sections );

			return $data;
		}

		/**
		 * [enqueue_scripts description]
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			if ( ! empty( $this->particle_sections ) ) {
				wp_enqueue_script( 'jet-tricks-ts-particles' );
			}

			jet_tricks_assets()->elements_data['sections'] = $this->sections_data;
		}

		public function enqueue_preview_scripts() {
			wp_enqueue_script( 'jet-tricks-ts-particles' );
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return object
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

/**
 * Returns instance of Jet_Tricks_Elementor_Section_Extension
 *
 * @return object
 */
function jet_tricks_elementor_section_extension() {
	return Jet_Tricks_Elementor_Section_Extension::get_instance();
}
