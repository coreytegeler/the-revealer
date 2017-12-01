<?php
show_admin_bar( false );
add_theme_support( 'post-thumbnails', array( 'post', 'page', 'event' ) ); 
add_image_size( 'thumb', 800, 500, true );
add_image_size( 'small', 1000, 1000, false );

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

function get_contributors_list( $writ = false, $cont = false ) {
  $contributors = [];
  if( $writ && have_rows( 'writers' ) ) {
    while( have_rows( 'writers') ) : the_row();
      $contributors[] = get_sub_field( 'name' );
    endwhile;
  }

  if( $cont && have_rows( 'contributors' ) ) {
    while( have_rows( 'contributors') ) : the_row();
      $contributors[] = get_sub_field( 'name' );
    endwhile;
  }

  return implode( $contributors, ', ' );
}

function get_tags_list() {
  $tags_array = [];
  if( $tags = get_the_tags() ) {
    foreach ( $tags as  $tag ) {
      $tags_array[] = $tag->name;
    }
    
  }
  return implode( $tags_array, ', ' ); 
  
}

function urlify( $url ) {
  if ( !preg_match( '~^(?:f|ht)tps?://~i', $url ) ) {
    $url = 'http://' . $url;
  }
  return $url;
}

function is_archived() {
  global $post;
  if( $post->post_type == 'post' ) {
    $this_date = get_the_date();
    $archive_date = new DateTime('08/01/2017');
    if( is_single() && strtotime( $this_date ) < strtotime( '08/01/2017' ) ) {
      return true;
    } else {
      return false;
    }
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
  // $vars[] .= 'page';
  return $vars;
}
add_filter( 'query_vars', 'add_query_vars' );

function register_events() {
  register_post_type( 'event',
    array(
      'labels' => array(
        'name' => __( 'Events' ),
        'singular_name' => __( 'Event' )
      ),
      'menu_position' => 5,
      'menu_icon' => 'dashicons-calendar-alt',
      'public' => true,
      'has_archive' => true,
      'supports' => array('title', 'thumbnail')
    )
  );
}
add_action( 'init', 'register_events' );

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

function register_issues() {
  register_taxonomy( 'issues', array('post'), array(
    'hierarchical' => true,
    'labels' => array(
      'name' => _x( 'Issues', 'taxonomy general name' ),
      'singular_name' => _x( 'Issue', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Issues' ),
      'all_items' => __( 'All Issues' ),
      'parent_item' => __( 'Parent Issue' ),
      'parent_item_colon' => __( 'Parent Issue:' ),
      'edit_item' => __( 'Edit Issue' ), 
      'update_item' => __( 'Update Issue' ),
      'add_new_item' => __( 'Add New Issue' ),
      'new_item_name' => __( 'New Issue Name' ),
      'menu_name' => __( 'Issues' ),
    ),
    'show_ui' => true,
    'show_admin_issue' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'issue' ),
  ));
}
add_action( 'init', 'register_issues' );


if( function_exists('acf_add_options_page') ) {
  acf_add_options_page(); 
}

function register_nav() {
  register_nav_menu( 'navigation', __( 'Navigation' ) );
}
add_action( 'init', 'register_nav' );

function add_custom_tax_filters() {
  global $typenow;
  $taxonomies = array( 'columns', 'issues' );
  if( $typenow == 'post' ){
    foreach( $taxonomies as $tax_slug ) {
      $tax_obj = get_taxonomy( $tax_slug );
      $tax_name = $tax_obj->labels->name;
      $terms = get_terms( $tax_slug );
      if( $tax_slug == 'issues' ) {
        usort( $terms, function($a, $b) {
          $a_date = get_field( 'date', $a );
          $b_date = get_field( 'date', $b );
          $a_datetime = new DateTime( $a_date );
          $b_datetime = new DateTime( $b_date );
          if( $a_datetime == $b_datetime ) {
            return 0;
          }
          return $a_datetime > $b_datetime ? -1 : 1;
        });
      }

      if( count( $terms ) > 0 ) {
        echo "<select name='$tax_slug' id='$tax_slug' class='postform' style='max-width:150px;'>";
        echo "<option value=''>Show All $tax_name</option>";
        foreach ( $terms as $term ) { 
          echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name . '</option>'; 
        }
        echo "</select>";
      }
    }
  }
}
add_action( 'restrict_manage_posts', 'add_custom_tax_filters' );

function prefix_reset_metabox_positions(){
  delete_user_meta( 1, 'meta-box-order_post' );
  delete_user_meta( 1, 'meta-box-order_page' );
  delete_user_meta( 1, 'meta-box-order_custom_post_type' );
}
add_action( 'admin_init', 'prefix_reset_metabox_positions' );

flush_rewrite_rules( false );
// flush_rewrite_rules();
?>