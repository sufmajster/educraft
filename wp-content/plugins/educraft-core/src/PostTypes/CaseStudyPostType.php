<?php
/**
 * Case Study custom post type.
 *
 * @package EduCraft_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CaseStudyPostType {

	public function register(): void {
		$labels = array(
			'name'                     => _x( 'Case Studies', 'post type general name', 'educraft-core' ),
			'singular_name'            => _x( 'Case Study', 'post type singular name', 'educraft-core' ),
			'menu_name'                => _x( 'Case Studies', 'admin menu', 'educraft-core' ),
			'name_admin_bar'           => _x( 'Case Study', 'add new on admin bar', 'educraft-core' ),
			'add_new'                  => _x( 'Add New', 'case study', 'educraft-core' ),
			'add_new_item'             => __( 'Add New Case Study', 'educraft-core' ),
			'new_item'                 => __( 'New Case Study', 'educraft-core' ),
			'edit_item'                => __( 'Edit Case Study', 'educraft-core' ),
			'view_item'                => __( 'View Case Study', 'educraft-core' ),
			'all_items'                => __( 'All Case Studies', 'educraft-core' ),
			'search_items'             => __( 'Search Case Studies', 'educraft-core' ),
			'not_found'                => __( 'No case studies found.', 'educraft-core' ),
			'not_found_in_trash'       => __( 'No case studies found in Trash.', 'educraft-core' ),
			'item_published'           => __( 'Case study published.', 'educraft-core' ),
			'item_published_privately' => __( 'Case study published privately.', 'educraft-core' ),
			'item_reverted_to_draft'   => __( 'Case study reverted to draft.', 'educraft-core' ),
			'item_scheduled'           => __( 'Case study scheduled.', 'educraft-core' ),
			'item_updated'             => __( 'Case study updated.', 'educraft-core' ),
		);

		$args = array(
			'labels'       => $labels,
			'public'       => true,
			'has_archive'  => true,
			'show_in_rest' => true,
			'menu_icon'    => 'dashicons-portfolio',
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'rewrite'      => array( 'slug' => 'case-studies' ),
		);

		register_post_type( 'case-study', $args );
	}
}
