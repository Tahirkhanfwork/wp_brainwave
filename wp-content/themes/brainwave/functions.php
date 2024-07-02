<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !function_exists( 'child_theme_configurator_css' ) ):
function child_theme_configurator_css() {
    // wp_dequeue_style( 'kadence-global' );
    // wp_deregister_style( 'kadence-global' );
    // wp_dequeue_style( 'kadence-content' );
    // wp_deregister_style( 'kadence-content' );
    // wp_dequeue_style( 'kadence-footer' );
    // wp_deregister_style( 'kadence-footer' );

    // Enqueue child theme styles
    wp_enqueue_style( 'bootstrap-min-style', get_stylesheet_directory_uri() . '/assets/css/bootstrap.min.css');
    wp_enqueue_style( 'css-style', get_stylesheet_directory_uri() . '/assets/css/style.css');
    wp_enqueue_style( 'tiny-slider-style', get_stylesheet_directory_uri() . '/assets/css/tiny-slider.css');
    wp_enqueue_style( 'bootstrapcdn-style', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

    // Enqueue child theme scripts
    wp_enqueue_script( 'bootstrap-js', get_stylesheet_directory_uri() . '/assets/js/bootstrap.bundle.min.js');
    wp_enqueue_script( 'tiny-slider-js', get_stylesheet_directory_uri() . '/assets/js/tiny-slider.js');
    wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js');
}
endif;
add_action('wp_enqueue_scripts', 'child_theme_configurator_css', 20);

//adding Homepage Metaboxes
require("inc/homepage_metabox/hero_metabox.php");
require("inc/homepage_metabox/product_metabox.php");

//adding Custom post type for shop
require("inc/shop_cpt/shop.php");

//adding my API file
require("api.php");

?>
