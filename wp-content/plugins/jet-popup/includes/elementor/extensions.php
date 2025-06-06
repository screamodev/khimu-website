<?php
namespace Jet_Popup;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Elementor_Extensions {

	/**
	 * Widgets Data
	 *
	 * @var array
	 */
	public $widgets_data = [];

	public $elements_data = [
		'sections' => [],
		'columns'  => [],
		'widgets'  => [],
	];

	/**
	 * [$default_widget_settings description]
	 * @var array
	 */
	public $default_widget_settings = [
		'jet_attached_popup'          => '',
		'jet_trigger_type'            => 'click-self',
		'jet_trigger_custom_selector' => '',
	];

	/**
	 * [$avaliable_widgets description]
	 * @var array
	 */
	public $avaliable_widgets = [
		'heading'           => '.elementor-heading-title',
		'button'            => '.elementor-button-link',
		'icon'              => '.elementor-image',
		'image'             => 'img',
		'animated-headline' => '.elementor-headline',
		'flip-box'           => '.elementor-flip-box__button',
		'call-to-action'    => '.elementor-cta__button',
	];

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
	public function __construct() {
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'widget_extensions' ], 10, 2 );
		add_action( 'elementor/frontend/widget/before_render', [ $this, 'widget_before_render' ] );
		add_action( 'elementor/frontend/container/before_render', array( $this, 'widget_before_render' ) );
		add_action( 'elementor/element/container/section_layout/after_section_end', array( $this, 'widget_extensions' ), 10, 2 );
	}

	/**
	 * After section_layout callback
	 *
	 * @param  object $obj
	 * @param  array $args
	 * @return void
	 */
	public function widget_extensions( $obj, $args ) {

		$elType = $obj->get_type();

		$trigger_options = [
			'none'           => __( 'None', 'jet-popup' ),
			'click'          => __( 'Click On Button', 'jet-popup' ),
			'click-self'     => __( 'Click On Widget', 'jet-popup' ),
			'click-selector' => __( 'Click On Custom Selector', 'jet-popup' ),
			'hover'          => __( 'Hover', 'jet-popup' ),
			'scroll-to'      => __( 'Scroll To Widget', 'jet-popup' ),
		];

		if ( 'section' === $elType || 'container' === $elType ) {
			$trigger_options = [
				'none'           => __( 'None', 'jet-popup' ),
				'click-self'     => __( 'Click On Container', 'jet-popup' ),
				'click-selector' => __( 'Click On Custom Selector', 'jet-popup' ),
				'hover'          => __( 'Hover', 'jet-popup' ),
				'scroll-to'      => __( 'Scroll To Container', 'jet-popup' ),
			];
		}

		$obj->start_controls_section(
			'widget_jet_popup',
			[
				'label' => esc_html__( 'JetPopup', 'jet-popup' ),
				'tab'   => \Elementor\Controls_Manager::TAB_ADVANCED,
			]
		);

		do_action( 'jet-popup/editor/widget-extension/before-base-controls', $obj, $args );

		$obj->add_control(
			'jet_attached_popup',
			[
				'label'       => __( 'Attached Popup', 'jet-popup' ),
				'type'        => 'jet-query',
				'query_type'  => 'post',
				'query'       => apply_filters( 'jet_popup_default_query_args',
					[
						'post_type'      => jet_popup()->post_type->slug(),
						'order'          => 'DESC',
						'orderby'        => 'date',
						'posts_per_page' => - 1,
						'post_status'    => 'publish',
					]
				),
				'edit_button' => [
					'active' => true,
					'label'  => __( 'Edit Popup', 'jet-popup' ),
				],
			]
		);

		$obj->add_control(
			'jet_trigger_type',
			[
				'label'   => __( 'Trigger Type', 'jet-popup' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'click-self',
				'options' => $trigger_options,
			]
		);

		$obj->add_control(
			'jet_trigger_custom_selector',
			[
				'label'       => __( 'Custom Selector', 'jet-popup' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Custom Selector', 'jet-popup' ),
				'condition'   => [
					'jet_trigger_type' => 'click-selector',
				]
			]
		);

		do_action( 'jet-popup/editor/widget-extension/after-base-controls', $obj, $args );

		$obj->end_controls_section();
	}

	/**
	 * [widget_before_render description]
	 * @param  [type] $widget [description]
	 * @return [type]         [description]
	 */
	public function widget_before_render( $widget ) {
		$data     = $widget->get_data();
		$settings = $data['settings'];
		$widget_settings = array();

		if ( ! empty( $settings['jet_attached_popup'] ) ) {
			$settings = wp_parse_args( $settings, $this->default_widget_settings );

			$settings = apply_filters( 'jet-popup/extensions/settings', $settings );

			$widget_settings['attached-popup']          = 'jet-popup-' . $settings['jet_attached_popup'];
			$widget_settings['trigger-type']            = isset( $settings['jet_trigger_type'] ) ? $settings['jet_trigger_type'] : 'click-self';
			$widget_settings['trigger-custom-selector'] = $settings['jet_trigger_custom_selector'];

			$widget->add_render_attribute( '_wrapper', array(
				'class' => 'jet-popup-target',
			) );

			$widget_settings = apply_filters(
				'jet-popup/widget-extension/widget-before-render-settings',
				$widget_settings,
				$settings
			);

			/*if ( wp_doing_ajax() || \Jet_Popup_Utils::wp_doing_rest() ) {
				$widget->add_render_attribute( '_wrapper', array(
					'data-jet-popup' => esc_attr( json_encode( $widget_settings ) ),
				) );
			}*/

			$widget->add_render_attribute( '_wrapper', array(
				'data-jet-popup' => esc_attr( json_encode( $widget_settings ) ),
			) );
		}

		if ( ! empty( $widget_settings ) ) {
			$this->elements_data['widgets'][ $data['id'] ] = $widget_settings;
		}
	}

	/**
	 * [empty_templates_message description]
	 * @return [type] [description]
	 */
	public function empty_templates_message() {
		return '<div id="elementor-widget-template-empty-templates">
				<div class="elementor-widget-template-empty-templates-title">' . esc_html__( 'You Haven’t Created Popup Yet.', 'jet-popup' ) . '</div>
			</div>';
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
