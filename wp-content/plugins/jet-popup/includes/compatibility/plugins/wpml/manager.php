<?php
namespace Jet_Popup\Compatibility;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class WPML {

	/**
	 *
	 */
	public function __construct() {

		if ( ! defined( 'WPML_ST_VERSION' ) ) {
			return false;
		}

		if ( class_exists( 'WPML_Elementor_Module_With_Items' ) ) {
			$this->load_wpml_integration_classes();
		}

		add_filter( 'wpml_elementor_widgets_to_translate',  array( $this, 'add_translatable_nodes' ) );
		add_filter( 'jet-popup/get_conditions/template_id', array( $this, 'set_translated_popup' ) );

		add_action( 'jet-popup/create-popup/created', array( $this, 'get_created_popup' ), 10, 2 );
		add_filter( 'jet-popup/extensions/settings', array( $this, 'set_translated_attached_popup' ) );
	}

	/**
	 * Load wpml required files.
	 *
	 * @return void
	 */
	public function load_wpml_integration_classes(){
		require jet_popup()->plugin_path( 'includes/compatibility/plugins/wpml/mailchimp.php' );
	}

	/**
	 * Get the language info of the original post
	 *
	 * @return void
	 */

	 public function get_created_popup( $popup_id, $obj ) {

		$default_language = apply_filters( 'wpml_default_language', NULL );

		do_action( 'wpml_set_element_language_details', array (
			'element_id'   => $popup_id,
			'element_type' => 'post_' . $obj->slug() ,
			'trid' => false,
			'language_code' => $default_language,
			'source_language_code' => $default_language,
		) );
	}

	/**
	 * Set translated attached popup ID to show
	 *
	 * @param int|string $settings
	 *
	 * @return int
	 */
	public function set_translated_attached_popup( $settings ){
		$settings['jet_attached_popup'] = apply_filters( 'wpml_object_id', $settings['jet_attached_popup'], 'jet-popup' );

		return $settings;
	}

	/**
	 * Set translated popup ID to show
	 *
	 * @param int|string $popup_id Popup ID
	 *
	 * @return int
	 */
	public function set_translated_popup( $popup_id ) {
		return apply_filters( 'wpml_object_id', $popup_id, get_post_type( $popup_id ), true );
	}

	/**
	 * Add translation strings
	 *
	 * @param  array $nodes
	 * @return array
	 */
	public function add_translatable_nodes( $nodes ) {

		$nodes['jet-popup-action-button'] = array(
			'conditions' => array(
				'widgetType' => 'jet-popup-action-button'
			),
			'fields' => array(
				array(
					'field'       => 'button_text',
					'type'        => esc_html__( 'Popup Action Button: Button text', 'jet-popup' ),
					'editor_type' => 'LINE',
				),
				array(
					'field'       => 'button_link',
					'type'        => esc_html__( 'Popup Action Button: Button Link', 'jet-popup' ),
					'editor_type' => 'LINK',
				),
			),
		);

		$nodes['jet-popup-mailchimp'] = array(
			'conditions' => array(
				'widgetType' => 'jet-popup-mailchimp'
			),
			'fields' => array(
				array(
					'field'       => 'redirect_url',
					'type'        => esc_html__( 'Mailchimp: Redirect Url', 'jet-popup' ),
					'editor_type' => 'LINE',
				),
				array(
					'field'       => 'email_label',
					'type'        => esc_html__( 'Mailchimp: E-Mail Label', 'jet-popup' ),
					'editor_type' => 'LINE',
				),
				array(
					'field'       => 'email_placeholder',
					'type'        => esc_html__( 'Mailchimp: E-Mail Placeholder', 'jet-popup' ),
					'editor_type' => 'LINE',
				),
				array(
					'field'       => 'submit_button_text',
					'type'        => esc_html__( 'Mailchimp: Submit Text', 'jet-popup' ),
					'editor_type' => 'LINK',
				),
			),
			'integration-class' => 'Jet_Popup_WPML_Mailchimp',
		);

		return $nodes;
	}

}
