<?php
/**
 * This file contains only functions related to setting up the theme.
 * Any other functions that need to be loaded should be placed inside
 * the /inc directory.
 */

declare(strict_types=1);

/* ## HOOK: after_setup_theme ##
This hook fires when the theme is initialized.
This can be used for init actions that need to happen when a theme
is launched.
*/

// Import dependencies
if (!function_exists('esb_dependency_setup')) {
    function esb_dependency_setup() {

        // NOTE: It's possible to dynamically load files from a directory, but
        // I've opted not to since that might make it harder to troubleshoot if
        // a necessary file was removed from that directory.
        $required_files = array(
            get_stylesheet_directory() . '/classes/class-esb-format-pagination.php',
            get_stylesheet_directory() . '/classes/class-esb-format-post.php',
            get_stylesheet_directory() . '/classes/class-esb-html-helper.php',
            get_stylesheet_directory() . '/classes/class-esb-nav-walker.php',
            get_stylesheet_directory() . '/inc/constant.php',
        );
        
        // Require files
        foreach ($required_files as $dependency) {
            require_once($dependency);
        }
    }
}

add_action( 'after_setup_theme', 'esb_dependency_setup' );

// Add support for theme features
if (!function_exists('esb_theme_setup')) {
    function esb_theme_setup() {
		// Let Wordpress manage site title
		add_theme_support( 'title-tag' );
		// // Add support for custom logo
        // TODO: Add logo support
		// add_theme_support( 'custom-logo' );

        register_nav_menus( array(
            'primary-menu' => 'Primary Menu'
        ) );
    }
}

add_action( 'after_setup_theme', 'esb_theme_setup' );

/* ## HOOK: wp_enqueue_scripts ##
These hook fires when scripts and styles are enqueued.
This can be used to enqueue both scripts and styles.  Use
the dependency array to manage styles/scripts that need to
be loaded after styles/scripts.
*/

// Add CSS and JS files
function esb_enqueue_scripts() {
	wp_enqueue_style(
        'bootstrap_css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css'
    );
    wp_enqueue_style(
        'esb_main_css',
        get_stylesheet_uri(),
        array('bootstrap_css')
    );
    wp_enqueue_script(
        'bootstrap_js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js',
        array(),
        false,
        array(
            'strategy' => 'defer'
        )
    );
}

add_action( 'wp_enqueue_scripts', 'esb_enqueue_scripts' );
