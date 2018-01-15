<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
<!-- /**
 * @license
 * MyFonts Webfont Build ID 3494945, 2017-12-08T17:55:29-0500
 * 
 * The fonts listed in this notice are subject to the End User License
 * Agreement(s) entered into by the website owner. All other parties are 
 * explicitly restricted from using the Licensed Webfonts(s).
 * 
 * You may obtain a valid license at the URLs below.
 * 
 * Webfont: ClarendonBTWXX-Roman by Bitstream
 * URL: https://www.myfonts.com/fonts/bitstream/clarendon/roman-148722/
 * Copyright: Copyright &#x00A9; 2015 Monotype Imaging Inc. All rights reserved.
 * Licensed pageviews: 10,000
 * 
 * 
 * License: https://www.myfonts.com/viewlicense?type=web&buildid=3494945
 * 
 * © 2017 MyFonts Inc
*/ -->
<?php
global $post;
$page_type = $post->post_type;
$page_slug = $post->post_name;
$og_type = 'website';
if( is_home() ) {
	$page_slug = 'home';
} else if( is_search() ) {
	$page_slug = 'search';
} else if( is_404() ) {
	$page_slug = '404';
} else if( $page_type == 'post' && !is_tax() ) {
	$page_type = 'article';
	$og_type = 'article';
} else {
	$page_slug = $post->post_name;;
}
$page_title = get_bloginfo( 'name' );
$og_title = $page_title;
$post_title = get_the_title();
if( $page_type == 'article' ) {
	if( $column = get_the_terms( $post, 'columns' ) ) {
		$article_title = $column[0]->name . ': ' . $post_title;
	} else {
		$article_title = $post_title;
	}
	$page_title = $article_title . ' — ' . $page_title;
	$og_title =  $article_title . ' — ' . $og_title;
} else if( $page_slug != 'home' ) {
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
	$article_id = $post->ID;
	echo '<meta property="article:published_time" content="' . get_the_date( 'c' ) . '" />';
	echo '<meta property="article:modified_time" content="' . get_the_modified_date( 'c' ) . '" />';
	echo '<meta property="article:author" content="' . get_contributors_list( $article_id, true, true ) . '" />';
	echo '<meta property="article:section" content="Religion" />';
	echo '<meta property="article:tag" content="' . get_tags_list() . '" />';
}
echo '<meta property="og:url" content="http://www.imdb.com/title/tt0117500/" />';
echo '<meta property="og:image" content="http://ia.media-imdb.com/images/rock.jpg" />';
echo '<script type="text/javascript" src="' . get_template_directory_uri() . '/MyFontsWebfontsKit.js"/>';
wp_head();
?>
</head>
<body <?php body_class( array( $page_slug, $page_type ) ) ?> data-site-url="<?php echo get_site_url() ?>">
<?php
get_template_part( 'parts/header' );
echo '<div id="wrapper">';
	wp_reset_query();
	if( $page_slug == 'articles' ) {
		get_template_part( 'parts/filters' );
	}
	echo '<main>';
?>