<?php
namespace Jet_Theme_Core;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Structures {

	/**
	 * @var null
	 */
	private $_structures = null;

	/**
	 * Structures constructor.
	 */
	public function __construct() {
		$this->register_structures();
	}

	/**
	 * Register default data structures
	 *
	 * @return void
	 */
	public function register_structures() {

		$base_path = jet_theme_core()->plugin_path( 'includes/template-structures/structures/' );

		require $base_path . 'base.php';

		$structures = apply_filters( 'jet-theme-core/template-structures/structures-list', [
			'\Jet_Theme_Core\Structures\Page'    => $base_path . 'page.php',
			'\Jet_Theme_Core\Structures\Header'  => $base_path . 'header.php',
			'\Jet_Theme_Core\Structures\Footer'  => $base_path . 'footer.php',
			'\Jet_Theme_Core\Structures\Section' => $base_path . 'section.php',
			'\Jet_Theme_Core\Structures\Archive' => $base_path . 'archive.php',
			'\Jet_Theme_Core\Structures\Single'  => $base_path . 'single.php',
		] );

		foreach ( $structures as $class => $file ) {
			require $file;
			$this->register_structure( $class );
		}

		do_action( 'jet-theme-core/structures/register', $this );

	}

	/**
	 * @param $class
	 */
	public function register_structure( $class ) {
		$instance = new $class;

		$this->_structures[ $instance->get_id() ] = $instance;

		if ( true === $instance->is_location() ) {
			$id = $instance->location_name();
			jet_theme_core()->locations->register_location( $id, $instance );

			/**
			 * Fires after locations register
			 */
			do_action( 'jet-theme-core/locations/register', $id, $instance );
		}
	}

	/**
	 * Returns all structures data
	 *
	 * @return array
	 */
	public function get_structures() {
		return $this->_structures;
	}

	/**
	 * Returns all structures data
	 *
	 * @return object
	 */
	public function get_structure( $id ) {
		return isset( $this->_structures[ $id ] ) ? $this->_structures[ $id ] : false;
	}

	/**
	 * Return structures prepared for post type page tabs
	 * @return [type] [description]
	 */
	public function get_structures_for_post_type() {
		$result = [];

		foreach ( $this->_structures as $id => $structure ) {
			$result[ $id ] = $structure->get_single_label();
		}

		return $result;
	}

	/**
	 * @return int[]|string[]
	 */
	public function get_structure_types() {
		$raw_structure_list = $this->get_structures_for_post_type();
		$structure_types = array_merge( array_keys( $raw_structure_list ), [ 'unassigned' ] );

		return $structure_types;
	}

	/**
	 * @return array
	 */
	public function get_template_type_options() {

		// Exclude structures
		$structures = array_filter( $this->_structures, function ( $structure ) {
			$exclude_map = [];

			return ! in_array( $structure->get_id(), $exclude_map );
		} );

		return array_values( array_map( function ( $structure ) {
			return [
				'label' => $structure->get_single_label(),
				'value' => $structure->get_id(),
			];
		}, $structures ) );
	}

	/**
	 * Return structures prepared for popup tabs
	 *
	 * @return [type] [description]
	 */
	public function get_structures_for_library() {

		$result = [];

		foreach ( $this->_structures as $id => $structure ) {
			$sources = $structure->get_sources();

			if ( empty( $sources ) ) {
				continue;
			}

			$result[ $id ] = [
				'title'    => $structure->get_plural_label(),
				'data'     => array(),
				'sources'  => $sources,
				'settings' => $structure->library_settings(),
			];
		}

		return $result;

	}

	/**
	 * Get post structure name for current post ID.
	 *
	 * @param  int $post_id Post ID
	 * @return string
	 */
	public function get_post_structure( $post_id ) {

		$doc_type = get_post_meta( $post_id, '_jet_template_type', true );

		if ( ! $doc_type ) {
			return false;
		}

		$doc_structure = $this->get_structure( $doc_type );

		if ( ! $doc_structure ) {
			return false;
		} else {
			return $doc_structure;
		}

	}

	/**
	 * @return string[]
	 */
	public function get_structure_colors() {
		return [
			'jet_page' => '#6AAC1E',
			'jet_archive' => '#EE7B16',
			'jet_single' => '#4272F9',
			'jet_products_archive' => '#565FAD',
			'jet_single_product' => '#7956AD',
			'jet_products_card' => '#A456AD',
			'jet_products_checkout' => '#A456AD',
			'jet_products_checkout_endpoint' => '#A456AD',
			'jet_account_page' => '#A456AD',
			'jet_legacy_type' => '#CACBCD',
		];
	}

}
