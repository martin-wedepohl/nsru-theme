<?php
/**
 * Enqueue all styles and scripts starting with parent style
 */
function nsru_enqueue_styles() {
 
    $parent_style = 'parent-style';
 
    wp_enqueue_style( $parent_style, get_template_directory_uri()   . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ) );
}
add_action( 'wp_enqueue_scripts', 'nsru_enqueue_styles' );

/**
  * Set up NSRU Theme options
  *
  * Make it translation ready
  * Declare textdomain for this child theme.
  * Translations can be added to the /languages/ directory.
  */
function nsru_theme_setup() {
    load_child_theme_textdomain( 'nsru', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'nsru_theme_setup' );

/**
 * Add Yoast Breadcrumbs to the theme support
 */
add_theme_support( 'yoast-seo-breadcrumbs' );
