<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
<?php
global $post;
$page_type = $post->post_type;
$page_slug = $post->post_name;
$split_pages = array( 'article', 'articles' );
$wrapper_style = 'full';
$og_type = 'website';
if( is_home() ) {
	$page_slug = 'home';
} else if( is_search() || $page_slug == 'search' ) {
	$page_slug = 'search';
} else if( in_array( $page_slug, $split_pages ) ) {
	$wrapper_style = 'split';
} else if( is_404() ) {
	$page_slug = '404';
} else if( $page_type == 'post' && !is_tag() ) {
	$page_slug = 'article';
	$og_type = 'article';
	$wrapper_style = 'split';
} else {
	$page_slug = $post->post_name;;
}
$page_title = get_bloginfo( 'name' );
$og_title = $page_title;
if( $page_slug != 'home' ) {
	$page_title .= wp_title( '—', false );
	$og_title = wp_title( '—', false, 'right' ) . $og_title;
}
echo '<title>' . $page_title . '</title>';
echo '<meta charset="' . get_bloginfo( 'charset' ) . '">';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
if( $writer = get_field( 'writer' ) ) {
	echo '<meta name="author" content="' . $writer . '">';
}
echo '<meta name="subject" content="Subject">';
if( $description = get_bloginfo( 'description' ) ) {
	echo '<meta name="Description" content="' . $description. '">';
}
echo '<meta name="Classification" content="Classification ">';
echo '<meta name="Language" content="English">';
echo '<meta name="Designer" content="Corey Tegeler">';
if( $publisher_name = get_field( 'publisher_name', 'option' ) ) {
	echo '<meta name="Publisher" content="' . $publisher_name . '">';
}
echo '<link rel="profile" href="http://gmpg.org/xfn/11">';


echo '<meta property="og:title" content="' . $og_title . '" />';
echo '<meta property="og:type" content="' . $og_type .  '" />';
if( $og_type == 'article' ) {
	echo '<meta property="article:published_time" content="' . get_the_date( 'c' ) . '" />';
	echo '<meta property="article:modified_time" content="' . get_the_modified_date( 'c' ) . '" />';
	echo '<meta property="article:author" content="' . get_contributors_list() . '" />';
	echo '<meta property="article:section" content="Religion" />';
	echo '<meta property="article:tag" content="' . get_tags_list() . '" />';
}
echo '<meta property="og:url" content="http://www.imdb.com/title/tt0117500/" />';
echo '<meta property="og:image" content="http://ia.media-imdb.com/images/rock.jpg" />';
wp_head();
?>
</head>
<body <?php body_class( array( $page_slug, $page_type ) ) ?> data-site-url="<?php echo get_site_url() ?>">
<?php
get_template_part( 'parts/header' );
echo '<div id="wrapper" class="' . $wrapper_style . '">';
	wp_reset_query();
	if( $wrapper_style == 'split' ) {
		get_template_part( 'parts/side' );
	}
	echo '<main>';
?>