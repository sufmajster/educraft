<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CaseStudyFilterAjax {
	public function register_hooks(): void {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_ajax_educraft_case_study_filter', array( $this, 'handle_request' ) );
        add_action( 'wp_ajax_nopriv_educraft_case_study_filter', array( $this, 'handle_request' ) );
	}

	public function enqueue_scripts(): void {
		if ( ! is_post_type_archive( 'case-study' ) ) {
			return;
		}

		wp_enqueue_script(
			'educraft-case-study-filter',
			EDUCRAFT_CORE_URL . 'assets/js/case-study-filter.js',
			array(),
			EDUCRAFT_CORE_VERSION,
			true
		);

        wp_localize_script(
            'educraft-case-study-filter',
            'educraftCaseStudyFilter',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'educraft_case_study_filter' ),
            )
        );
	}

    public function handle_request(): void {
        if ( ! check_ajax_referer( 'educraft_case_study_filter', 'nonce', false ) ) {
            wp_send_json_error(
                array(
                    'message' => __( 'Invalid nonce.', 'educraft-core' ),
                ),
                403
            );
        }
    
        $industry = isset( $_POST['industry'] ) ? sanitize_text_field( wp_unslash( $_POST['industry'] ) ) : '';
    
        $query_args = array(
            'post_type'      => 'case-study',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'no_found_rows'  => true,
        );
    
        if ( '' !== $industry ) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'industry',
                    'field'    => 'slug',
                    'terms'    => array( $industry ),
                ),
            );
        }
    
        $query = new WP_Query( $query_args );
    
        ob_start();
    
        if ( $query->have_posts() ) {
            echo '<div class="case-study-archive__list">';
    
            while ( $query->have_posts() ) {
                $query->the_post();
                get_template_part( 'template-parts/case-study/card' );
            }
    
            echo '</div>';
        } else {
            echo '<p class="case-study-archive__empty">Brak case studies dla wybranej branży.</p>';
        }
    
        wp_reset_postdata();
    
        $html = ob_get_clean();
    
        wp_send_json_success(
            array(
                'html' => $html,
            )
        );
    }
}