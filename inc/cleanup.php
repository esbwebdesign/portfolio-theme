<?php

declare(strict_types=1);

/** CLEANUP UNNECESSARY HEAD CLUTTER **/
// If weird bugs, check here for stuff that might be necessary

// Remove unneeded emoji support
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );

// Remove REST API link
function remove_api () {
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
	remove_action( 'wp_head', 'wlwmanifest_link', 10 );
}
add_action( 'after_setup_theme', 'remove_api' );

// Remove extra CSS
function my_deregister_styles()    { 
	wp_deregister_style( 'classic-theme-styles' );
	wp_deregister_style( 'wp-block-library' );
}
add_action( 'wp_print_styles', 'my_deregister_styles', 100 );

// Disable Gutenberg style in header
function my_dequeue_styles() {
    wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'my_dequeue_styles', 100 );