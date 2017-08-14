<?php
function revealer_enqueue() {
	wp_enqueue_style( 'normalize', get_template_directory_uri() . '/assets/css/normalize.css' );
	wp_enqueue_style( 'style', get_stylesheet_uri() );
}
add_action( 'wp_enqueue_scripts', 'revealer_enqueue' );
wp_register_script( 'jquery', get_template_directory_uri() . '/assets/js/jquery-3.2.1.min.js' );
wp_register_script( 'imagesloaded', get_template_directory_uri() . '/assets/js/imagesloaded.js' );
wp_register_script( 'transit', get_template_directory_uri() . '/assets/js/transit.js' );
wp_register_script( 'carousel', get_template_directory_uri() . '/assets/js/carousel.js' );
wp_register_script( 'main', get_template_directory_uri() . '/assets/js/main.js' );

wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'imagesloaded' );
wp_enqueue_script( 'transit' );
wp_enqueue_script( 'carousel' );
wp_enqueue_script( 'main' );

show_admin_bar( false );
add_theme_support( 'post-thumbnails', array( 'post', 'page' ) ); 
add_image_size( 'thumb', 500, 350, true );

function register_contributors() {
  register_post_type( 'contributor',
    array(
      'labels' => array(
        'name' => __( 'Contributors' ),
        'singular_name' => __( 'Contributor' )
      ),
      'menu_position' => 5,
      'menu_icon' => 'dashicons-admin-users',
      'public' => true,
      'has_archive' => true,
      'supports' => array('title', 'editor', 'thumbnail')
    )
  );
}
add_action( 'init', 'register_contributors' );

// function remove_menu_items() {
    // remove_menu_page( 'edit.php' );
    // remove_menu_page( 'edit-comments.php' );
// }
// add_action( 'admin_menu', 'remove_menu_items' );

function register_nav() {
  register_nav_menu( 'navigation', __( 'Navigation' ) );
}
add_action( 'init', 'register_nav' );

flush_rewrite_rules( false );
?>