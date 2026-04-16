<?php
/**
 * Industry taxonomy for case studies.
 *
 * @package EduCraft_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class IndustryTaxonomy {

	public function register(): void {
		$labels = array(
			'name'              => _x( 'Industries', 'taxonomy general name', 'educraft-core' ),
			'singular_name'     => _x( 'Industry', 'taxonomy singular name', 'educraft-core' ),
			'search_items'      => __( 'Search Industries', 'educraft-core' ),
			'all_items'         => __( 'All Industries', 'educraft-core' ),
			'parent_item'       => __( 'Parent Industry', 'educraft-core' ),
			'parent_item_colon' => __( 'Parent Industry:', 'educraft-core' ),
			'edit_item'         => __( 'Edit Industry', 'educraft-core' ),
			'update_item'       => __( 'Update Industry', 'educraft-core' ),
			'add_new_item'      => __( 'Add New Industry', 'educraft-core' ),
			'new_item_name'     => __( 'New Industry Name', 'educraft-core' ),
			'menu_name'         => __( 'Industries', 'educraft-core' ),
			'not_found'         => __( 'No industries found.', 'educraft-core' ),
		);

		$args = array(
			'labels'       => $labels,
			'hierarchical' => true,
			'public'       => true,
			'show_in_rest' => true,
			'rewrite'      => array( 'slug' => 'industry' ),
		);

		register_taxonomy( 'industry', array( 'case-study' ), $args );
	}
}
