<?php
show_admin_bar( false );
add_theme_support( 'post-thumbnails', array( 'post', 'page', 'event' ) ); 

wp_register_script( 'jquery', get_template_directory_uri() . '/assets/js/jquery-3.2.1.min.js' );
wp_register_script( 'imagesloaded', get_template_directory_uri() . '/assets/js/imagesloaded.js' );
wp_register_script( 'transit', get_template_directory_uri() . '/assets/js/transit.js' );
wp_register_script( 'masonry', get_template_directory_uri() . '/assets/js/masonry.pkgd.min.js' );
wp_register_script( 'main', get_template_directory_uri() . '/assets/js/main.js' );

wp_localize_script( 'main', 'wp_api', array(
  'ajax_url' => admin_url( 'admin-ajax.php' )
) );

function revealer_enqueue() {
  wp_enqueue_script( 'jquery' );
  wp_enqueue_script( 'imagesloaded' );
  wp_enqueue_script( 'transit' );
  wp_enqueue_script( 'masonry' );
  wp_enqueue_script( 'main', '' );
  wp_enqueue_style( 'normalize', get_template_directory_uri() . '/assets/css/normalize.css' );
  wp_enqueue_style( 'style', get_stylesheet_uri(), '', 2.0 );
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

function get_articles_page() {
  $articles_page = get_page_by_path( 'articles' );
  if( $articles_page ) {
    return get_permalink( $articles_page );
  } else {
    return get_site_url() . '/articles/';
  }
}

function excerpt( $limit ) {
  $excerpt = explode( ' ', get_the_excerpt( $post ), $limit );
  if ( count( $excerpt ) >= $limit ) {
    array_pop( $excerpt );
    $excerpt = implode( ' ', $excerpt ) . '...';
  } else {
    $excerpt = implode( ' ' , $excerpt );
  }	
  $excerpt = preg_replace( '`[[^]]*]`', '', $excerpt);
  echo $excerpt;
}


function wrap_words( $str ) {
  $str = preg_replace( '([a-zA-Z.,!?0-9]+(?![^<]*>))', '<span class="word">$0</span>', $str );
  return $str;
}

function get_contributors_list( $id, $show_writers = false, $show_contributors = false, $links = false  ) {
  $contributors = [];
  if( !isset( $id ) || !$id ) {
    return false;
  }
  if( $show_writers && have_rows( 'writers', $id ) ) {
    while( have_rows( 'writers') ) : the_row();
      $contributors[] = get_sub_field( 'name' );
    endwhile;
  }

  if( $show_contributors && have_rows( 'contributors', $id ) ) {
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

function get_cat_list( $id, $link = false ) {
  $html = '';
  $categories = get_the_category( $id );
  $show_categories = array();
  $hidden = array( 'today', 'timeless', 'daily', 'in-the-world-2', 'uncategorized', 'timely', 'world-daily' );
  $articles_url = get_articles_page();
  if( $categories ) {
    foreach( $categories as $i => $cat ) {
      $slug = $cat->slug;
      if( $slug == 'features' ) {
        array_unshift( $show_categories, $cat );
      } else if( !in_array( $slug, $hidden ) ) {
        array_push( $show_categories, $cat );
      }
    }
    foreach( $show_categories as $i => $cat ) {
      $cat_name = get_field( 'singular', $cat );
      $cat_slug = $cat->slug;
      if( !$cat_name ) {
        $cat_name = $cat->name;
      }
      $html .= '<span class="' . $cat_slug . '">';
      if( $link ) {
        $cat_url = add_query_arg( 'category', $cat_slug, $articles_url );
        $html .= '<a href="' . $cat_url . '">' . $cat_name . '</a>';
      } else {
        $html .= $cat_name;
      }
      $html .= '</span>';
      if( $i < sizeof( $show_categories ) - 1 ) {
        $html .= ', ';
      }
    }
    // print_r( $categories );
  }
  return $html;
}

function get_recent_tags( $tags_count = null ) {
  global $wp_query;
  $tags = $used_tags = array();
  $articles_args = array(
    'posts_per_page' => 10
  );
  query_posts( $articles_args );
  if ( $wp_query->have_posts() ) {
    while ( $wp_query->have_posts() ) {
      the_post();
      $post_tags = get_the_tags();
      $post_tags = array_slice( $post_tags, 0, 10 );
      foreach( $post_tags as $i => $tag ) {
        $slug = $tag->slug;
        if( $tag->count > 5 && !in_array( $slug, $used_tags ) ) {
          array_push( $used_tags, $slug );
          array_push( $tags, $tag );
        }
      }
    }
  }
  wp_reset_query();
  if( $tags_count ) {
    shuffle( $tags );
    $tags = array_slice( $tags, 0, $tags_count );
  }
  return $tags;
}


function get_col_span( $id, $posts_query = null ) {
  if( !$posts_query ) {
    $posts_args = array(
      'post_type' => 'post',
      'posts_per_page' => -1,
      'orderby' => 'date',
      'order' => 'asc',
      'tax_query' => array(
        array(
          'taxonomy' => 'columns',
          'field' => 'id',
          'terms' => $id
        )
      )
    );
    $posts_query = new WP_Query( $posts_args );
  }
  $post_count = $posts_query->post_count;
  $first_date = $posts_query->posts[0]->post_date;
  $last_date = $posts_query->posts[$post_count-1]->post_date;
  $begin = date( 'F, Y', strtotime( $first_date ) );
  if( $active ) {
    $end = 'Present';
  } else {
    $end = date( 'F, Y', strtotime( $last_date ) );
  }
  return $begin . '&mdash;' . $end;
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
    'show_in_quick_edit' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'column' )
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
    'show_admin_column' => true,
    'show_in_quick_edit' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'issue' )
  ));
}
add_action( 'init', 'register_issues' );

function customize_menu() {
  add_menu_page( 'Issues', 'Issues', 'edit_posts', 'edit-tags.php?taxonomy=issues', '', 'dashicons-book-alt', 5 );
  add_menu_page( 'Columns', 'Columns', 'edit_posts', 'edit-tags.php?taxonomy=columns', '', 'dashicons-media-text', 5 );
}
add_action( 'admin_menu', 'customize_menu' );

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

function tiny_mce_remove_unused_formats($init) {
  $init['block_formats'] = 'Paragraph=p;Header=h1;Subeader=h2;';
  return $init;
}  
add_filter('tiny_mce_before_init', 'tiny_mce_remove_unused_formats' );

// function admin_style() {
//   wp_enqueue_style( 'admin-styles', get_template_directory_uri() . '/admin.css' );
// }
// add_action( 'admin_enqueue_scripts', 'admin_style' );


function add_admin_styles() {
  add_editor_style( get_template_directory_uri() . '/admin.css' );
}
add_action( 'init', 'add_admin_styles' );

// flush_rewrite_rules( false );
flush_rewrite_rules();
?>