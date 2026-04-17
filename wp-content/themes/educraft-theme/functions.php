<?php
/**
 * Theme bootstrap.
 *
 * @package EduCraft_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function educraft_theme_setup() {
	load_theme_textdomain( 'educraft-theme', get_template_directory() . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	register_nav_menus(
		array(
			'primary' => __( 'Menu główne', 'educraft-theme' ),
		)
	);
}
add_action( 'after_setup_theme', 'educraft_theme_setup' );

/**
 * Front-end styles (single stylesheet).
 */
function educraft_theme_enqueue_assets() {
	$ver = wp_get_theme()->get( 'Version' );

	wp_enqueue_style(
		'educraft-theme',
		get_stylesheet_uri(),
		array(),
		$ver
	);

	wp_enqueue_script(
		'educraft-theme-navigation',
		get_template_directory_uri() . '/assets/js/navigation.js',
		array(),
		$ver,
		true
	);
}
add_action( 'wp_enqueue_scripts', 'educraft_theme_enqueue_assets' );
