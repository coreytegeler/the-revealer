<?php
show_admin_bar( false );
add_theme_support( 'post-thumbnails', array( 'post', 'page' ) ); 
add_image_size( 'thumb', 800, 500, true );

wp_register_script( 'jquery', get_template_directory_uri() . '/assets/js/jquery-3.2.1.min.js' );
wp_register_script( 'imagesloaded', get_template_directory_uri() . '/assets/js/imagesloaded.js' );
wp_register_script( 'transit', get_template_directory_uri() . '/assets/js/transit.js' );
wp_register_script( 'masonry', get_template_directory_uri() . '/assets/js/masonry.pkgd.min.js' );
wp_register_script( 'carousel', get_template_directory_uri() . '/assets/js/carousel.js' );
wp_register_script( 'main', get_template_directory_uri() . '/assets/js/main.js' );

wp_localize_script( 'main', 'wp_api', array(
  'ajax_url' => admin_url( 'admin-ajax.php' )
) );

function revealer_enqueue() {
  wp_enqueue_style( 'normalize', get_template_directory_uri() . '/assets/css/normalize.css' );
  wp_enqueue_style( 'style', get_stylesheet_uri() );
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'imagesloaded' );
  wp_enqueue_script( 'transit' );
  wp_enqueue_script( 'masonry' );
  wp_enqueue_script( 'carousel' );
  wp_enqueue_script( 'main' );
}
add_action( 'wp_enqueue_scripts', 'revealer_enqueue' );

function api_query() {
  get_template_part( 'parts/discover' );
  // $rand_posts = get_posts( $rand_post_args );
  die();
}
add_action( 'wp_ajax_nopriv_api_query', 'api_query' );
add_action( 'wp_ajax_api_query', 'api_query' );


function get_word_count() {
  $content = get_post_field( 'post_content', $post->ID );
  $count = str_word_count( strip_tags( $content ) );
  return $count;
}

function get_read_time() {
  $count = get_word_count();
  $read_time = ceil( $count / 275 );
  if ($read_time == 1) {
    $units = ' minute';
  } else {
    $units = ' minutes';
  }
  $read_time .= $units;

  return $read_time;
}

function is_archived() {
  $this_date = get_the_date();
  $archive_date = new DateTime('08/01/2017');
  if( is_single() && strtotime( $this_date ) < strtotime( '08/01/2017' ) ) {
    return true;
  } else {
    return false;
  }
}

function add_query_vars( $vars ){
  $vars[] .= 'category';
  $vars[] .= 'y';
  $vars[] .= 'year';
  $vars[] .= 'tag';
  $vars[] .= 'column';
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars' );



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

function register_columns() {
  register_taxonomy( 'columns', array('post'), array(
    'hierarchical' => true,
    'labels' => array(
      'name' => _x( 'Columns', 'taxonomy general name' ),
      'singular_name' => _x( 'Column', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Columns' ),
      'all_items' => __( 'All Columns' ),
      'parent_item' => __( 'Parent Column' ),
      'parent_item_colon' => __( 'Parent Column:' ),
      'edit_item' => __( 'Edit Column' ), 
      'update_item' => __( 'Update Column' ),
      'add_new_item' => __( 'Add New Column' ),
      'new_item_name' => __( 'New Column Name' ),
      'menu_name' => __( 'Columns' ),
    ),
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'column' ),
  ));
}
add_action( 'init', 'register_columns' );

function register_nav() {
  register_nav_menu( 'navigation', __( 'Navigation' ) );
}
add_action( 'init', 'register_nav' );

flush_rewrite_rules( false );
// flush_rewrite_rules();
?>