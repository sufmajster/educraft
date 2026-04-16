<?php
/**
 * Plugin bootstrap.
 *
 * @package EduCraft_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin {

	public function init(): void {
		$case_study_post_type   = new CaseStudyPostType();
		$industry_taxonomy      = new IndustryTaxonomy();
		$case_study_filter_ajax = new CaseStudyFilterAjax();
		$checkout_fields        = new CheckoutFields();

		add_action( 'init', array( $case_study_post_type, 'register' ) );
		add_action( 'init', array( $industry_taxonomy, 'register' ), 11 );

		$case_study_filter_ajax->register_hooks();
		$checkout_fields->register_hooks();
	}
}
