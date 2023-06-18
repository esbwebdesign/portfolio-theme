<?php

declare(strict_types=1);

if ( ! function_exists( 'esb_portfolio_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 *
	 * @return void
	 */
	function esb_portfolio_setup() {
		// Let Wordpress manage site title
		add_theme_support( 'title-tag' );
		// Add support for custom logo
		add_theme_support( 'custom-logo' );
		
		/*
		 * Register navigation menus
		 */
		register_nav_menus( array(
			'primary'   => esc_html__( 'Primary Menu', 'esb_portfolio_setup' ),
			'secondary' => esc_html__( 'Secondary Menu', 'esb_portfolio_setup' )
		) );
	}
}

add_action( 'after_setup_theme', 'esb_portfolio_setup' );

// Add CSS and JS files
function add_theme_scripts() {
	wp_enqueue_style( 'bootstrap_css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' );
	wp_enqueue_style( 'main_css', get_stylesheet_uri() );
	wp_enqueue_script( 'bootstrap_js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js', array(), false, true );
}

add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );

// Bring in constants for use throughout code
// This must go before subsequent include/requires because it defines the __ROOT__ constant
require_once( dirname(__FILE__) . '\inc\constant.php' );

$include_list = [
	// Cleanup unneeded CSS and JS
	__ROOT__ . '\inc\cleanup.php' 
];

$require_list = [
	// Custom walkers
	__ROOT__ . '\classes\class-esb-nav-walker.php',
	__ROOT__ . '\classes\class-esb-cat-walker.php',
	__ROOT__ . '\classes\class-esb-comment-walker.php',
	// Formatting functions
	__ROOT__ . '\inc\format.php'
];

// include files
foreach ( $include_list as $dependency ) {
	require_once($dependency);
}

// Require files
foreach ( $require_list as $dependency ) {
	require_once($dependency);
}

// Remove default website field from comment form
// See https://developer.wordpress.org/reference/hooks/comment_form_default_fields/#comment-2565
function wpdocs_remove_website_field( $fields ) {
	unset( $fields['url'] );
	unset( $fields['email'] );
	return $fields;
}

add_filter( 'comment_form_default_fields', 'wpdocs_remove_website_field' );