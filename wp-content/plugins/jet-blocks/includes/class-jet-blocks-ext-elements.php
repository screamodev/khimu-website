<?php
/**
 * Jet_Blocks_Ext_Elements
 *
 * @package   package_name
 * @author    Cherry Team
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Blocks_Ext_Elements' ) ) {

	/**
	 * Define Jet_Blocks_Ext_Elements class
	 */
	class Jet_Blocks_Ext_Elements {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Has sticky section.
		 *
		 * @var bool
		 */
		private $has_sticky = false;

		/**
		 * Initialize hooks
		 *
		 * @return void
		 */
		public function init() {

			$avaliable_extensions = jet_blocks_settings()->get( 'avaliable_extensions', jet_blocks_settings()->default_avaliable_ext );

			if ( isset( $avaliable_extensions['column_order'] ) && 'true' === $avaliable_extensions['column_order'] ) {
				add_action( 'elementor/element/column/layout/before_section_end', array( $this, 'add_column_order_control' ), 10, 2 );
			}

			if ( isset( $avaliable_extensions['sticky_section'] ) && 'true' === $avaliable_extensions['sticky_section'] ) {

				add_action( 'elementor/element/section/section_advanced/after_section_end', array( $this, 'add_section_sticky_controls' ), 10, 2 );
				add_action( 'elementor/element/container/section_layout/after_section_end', array( $this, 'add_section_sticky_controls' ), 10, 2 );
				add_action( 'elementor/element/column/section_advanced/after_section_end', array( $this, 'add_section_sticky_controls' ), 10, 2 );
				add_action( 'elementor/frontend/element/before_render',                     array( $this, 'section_before_render' ) );
				add_action( 'elementor/frontend/section/before_render',                     array( $this, 'section_before_render' ) ); // for compatibility with Elementor 2.1.0
				add_action( 'elementor/frontend/container/before_render',                   array( $this, 'section_before_render' ) );
				add_action( 'elementor/frontend/column/before_render',                   array( $this, 'section_before_render' ) );
				add_action( 'elementor/frontend/before_enqueue_scripts',                    array( $this, 'enqueue_scripts' ), 9 );

			}

		}

		/**
		 * Add order control to column settings.
		 *
		 * @param object $element Element instance.
		 * @param array  $args    Element arguments.
		 */
		public function add_column_order_control( $element, $args ) {
			$element->add_responsive_control(
				'column_order',
				array(
					'label'       => esc_html__( 'Column Order', 'jet-blocks' ),
					'type'        => Elementor\Controls_Manager::NUMBER,
					'min'         => 0,
					'max'         => 100,
					'selectors'   => array(
						'{{WRAPPER}}.elementor-column' => 'order: {{VALUE}}',
					),
				),
				array(
					'position' => array(
						'at' => 'before',
						'of' => 'content_position',
					),
				)
			);
		}

		/**
		 * Add sticky controls to section settings.
		 *
		 * @param object $element Element instance.
		 * @param array  $args    Element arguments.
		 */
		public function add_section_sticky_controls( $element, $args ) {


			if ( \Elementor\Plugin::$instance->breakpoints && method_exists( \Elementor\Plugin::$instance->breakpoints, 'get_active_breakpoints')) {

				$active_breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
				$breakpoints_list   = array();

				foreach ($active_breakpoints as $key => $value) {
					$breakpoints_list[$key] = $value->get_label();
				}

				$breakpoints_list['desktop'] = 'Desktop';
				$breakpoints_list            = array_reverse($breakpoints_list);

				} else {

				$breakpoints_list = array(
					'desktop' => 'Desktop',
					'tablet'  => 'Tablet',
					'mobile'  => 'Mobile'
				);
			}

			$element->start_controls_section(
				'jet_sticky_section_settings',
				array(
					'label' => esc_html__( 'Jet Sticky', 'jet-blocks' ),
					'tab' => Elementor\Controls_Manager::TAB_ADVANCED,
				)
			);

			$element->add_control(
				'jet_sticky_section',
				array(
					'label'   => esc_html__( 'Sticky Section', 'jet-blocks' ),
					'type'    => Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'frontend_available' => true,
				)
			);

			$element->add_control(
				'jet_sticky_section_stop_at_parent_end',
				array(
					'label'   => esc_html__( 'Stop Sticky at Parent End', 'jet-blocks' ),
					'type'    => Elementor\Controls_Manager::SWITCHER,
					'default' => '',
					'description' => esc_html__( 'If enabled, the sticky effect will stop at the end of the parent section or container. By default, sticky effect stops at the next sticky element.', 'jet-blocks' ),
					'condition' => array(
						'jet_sticky_section' => 'yes',
					),
					'frontend_available' => true,
				)
			);

			$element->add_control(
				'jet_sticky_section_visibility',
				array(
					'label'       => esc_html__( 'Sticky Section Visibility', 'jet-blocks' ),
					'type'        => Elementor\Controls_Manager::SELECT2,
					'multiple'    => true,
					'label_block' => true,
					'default' => array( 'desktop', 'tablet', 'mobile' ),
					'options'     => $breakpoints_list,
					'condition' => array(
						'jet_sticky_section' => 'yes',
					),
					'frontend_available' => true,
				)
			);

			$element->add_control(
				'jet_sticky_section_z_index',
				array(
					'label'       => esc_html__( 'Z-index', 'jet-blocks' ),
					'type'        => Elementor\Controls_Manager::NUMBER,
					'placeholder' => 1100,
					'min'         => 1,
					'max'         => 10000,
					'step'        => 1,
					'selectors'   => array(
						'{{WRAPPER}}.jet-sticky-section--stuck' => 'z-index: {{VALUE}};',
					),
					'condition' => array(
						'jet_sticky_section' => 'yes',
					),
				)
			);

			$element->add_control(
				'jet_sticky_section_max_width',
				array(
					'label' => esc_html__( 'Max Width (px)', 'jet-blocks' ),
					'type'  => Elementor\Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 500,
							'max' => 2000,
						),
					),
					'selectors'   => array(
						'{{WRAPPER}}.jet-sticky-section--stuck' => 'max-width: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'jet_sticky_section' => 'yes',
					),
				)
			);

			$element->add_responsive_control(
				'jet_sticky_section_style_heading',
				array(
					'label'     => esc_html__( 'Sticky Section Style', 'jet-blocks' ),
					'type'      => Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => array(
						'jet_sticky_section' => 'yes',
					),
				)
			);

			$element->add_responsive_control(
				'jet_sticky_section_min_height',
				array(
					'label' => __( 'Minimum Height', 'jet-blocks' ),
					'type' => Elementor\Controls_Manager::SLIDER,
					'range' => array(
						'px' => array(
							'min' => 0,
							'max' => 1440,
						),
						'vh' => array(
							'min' => 0,
							'max' => 100,
						),
						'vw' => array(
							'min' => 0,
							'max' => 100,
						),
					),
					'size_units' => array( 'px', 'vh', 'vw' ),
					'selectors' => array(
						'{{WRAPPER}}.jet-sticky-section--stuck > .elementor-container' => 'min-height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.jet-sticky-section--stuck' => 'min-height: {{SIZE}}{{UNIT}};',
					),
					'condition' => array(
						'jet_sticky_section' => 'yes',
					),
					'hide_in_inner' => true,
				)
			);

			$element->add_responsive_control(
				'jet_sticky_section_margin',
				array(
					'label'      => esc_html__( 'Margin', 'jet-blocks' ),
					'type'       => Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', '%' ),
					'allowed_dimensions' => 'vertical',
					'placeholder' => array(
						'top'    => '',
						'right'  => 'auto',
						'bottom' => '',
						'left'   => 'auto',
					),
					'selectors' => array(
						'{{WRAPPER}}.jet-sticky-section--stuck' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom: {{BOTTOM}}{{UNIT}};',
					),
					'condition' => array(
						'jet_sticky_section' => 'yes',
					),
				)
			);

			$element->add_responsive_control(
				'jet_sticky_section_padding',
				array(
					'label'      => esc_html__( 'Padding', 'jet-blocks' ),
					'type'       => Elementor\Controls_Manager::DIMENSIONS,
					'size_units' => array( 'px', 'em', '%' ),
					'selectors'  => array(
						'{{WRAPPER}}.jet-sticky-section--stuck' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					),
					'condition' => array(
						'jet_sticky_section' => 'yes',
					),
				)
			);

			$element->add_group_control(
				Elementor\Group_Control_Background::get_type(),
				array(
					'name'      => 'jet_sticky_section_background',
					'selector'  => '{{WRAPPER}}.jet-sticky-section--stuck',
					'condition' => array(
						'jet_sticky_section' => 'yes',
					),
				)
			);

			$element->add_group_control(
				Elementor\Group_Control_Box_Shadow::get_type(),
				array(
					'name'      => 'jet_sticky_section_box_shadow',
					'selector'  => '{{WRAPPER}}.jet-sticky-section--stuck',
					'condition' => array(
						'jet_sticky_section' => 'yes',
					),
				)
			);

			$element->add_control(
				'jet_sticky_section_transition',
				array(
					'label'   => esc_html__( 'Transition Duration', 'jet-blocks' ),
					'type'    => Elementor\Controls_Manager::SLIDER,
					'default' => array(
						'size' => 0.1,
					),
					'range' => array(
						'px' => array(
							'max'  => 3,
							'step' => 0.1,
						),
					),
					'selectors' => array(
						'{{WRAPPER}}.jet-sticky-section--stuck.jet-sticky-transition-in, {{WRAPPER}}.jet-sticky-section--stuck.jet-sticky-transition-out' => 'transition: margin {{SIZE}}s, padding {{SIZE}}s, background {{SIZE}}s, box-shadow {{SIZE}}s',
						'{{WRAPPER}}.jet-sticky-section--stuck.jet-sticky-transition-in > .elementor-container, {{WRAPPER}}.jet-sticky-section--stuck.jet-sticky-transition-out > .elementor-container' => 'transition: min-height {{SIZE}}s',
					),
					'condition' => array(
						'jet_sticky_section' => 'yes',
						'jet_sticky_section_background_background!' => 'gradient'
					),
				)
			);

			$element->end_controls_section();
		}

		/**
		 * Elementor before section render callback.
		 *
		 * @param object $element Section instance.
		 */
		public function section_before_render( $element ) {
			if ( ! in_array( $element->get_name(), array( 'section', 'container', 'column' ) ) ) {
				return;
			}

			if ( 'yes' === $element->get_settings( 'jet_sticky_section' ) ) {
				$element->add_render_attribute( '_wrapper', array(
					'class' => 'jet-sticky-section',
					'style' => 'height: fit-content;',
				) );

				$this->has_sticky = true;
			}
		}

		/**
		 * Enqueue scripts.
		 *
		 * @return void
		 */
		public function enqueue_scripts() {

			if ( $this->has_sticky ) {

				$min_suffix = jet_blocks_tools()->is_script_debug() ? '' : '.min';

				wp_enqueue_script(
					'jsticky',
					jet_blocks()->plugin_url( 'assets/js/lib/jsticky/jquery.jsticky' . $min_suffix . '.js' ),
					array( 'jquery' ),
					'1.1.0',
					true
				);
			}
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
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
 * Returns instance of Jet_Blocks_Ext_Elements
 *
 * @return object
 */
function jet_blocks_ext_elements() {
	return Jet_Blocks_Ext_Elements::get_instance();
}
