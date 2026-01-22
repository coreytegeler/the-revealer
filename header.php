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
	<?php global $post; ?>
	<?php
	$page_type = $post ? $post->post_type : null;
	$page_slug = $post ? $post->post_name : null;
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
	$site_title = get_bloginfo( 'name' );
	$og_title = $site_title;
	$post_title = get_the_title();
	$description = get_bloginfo( 'description' );
	$url = get_the_permalink();
	$thumbnail_url = get_stylesheet_directory_uri() . '/assets/images/social.jpg';
	$thumbnail_width = 1200;
	$thumbnail_height = 630;
	if( $page_type == 'article' ) {
		if( $column = get_the_terms( $post, 'columns' ) ) {
			$article_title = $column[0]->name . ': ' . $post_title;
		} else {
			$article_title = $post_title;
		}
		$site_title = $article_title . ' — ' . $site_title;
		$og_title =  $article_title . ' — ' . $og_title;
		$description = wp_strip_all_tags( get_the_excerpt() );
		$thumbnail_attachment = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'article-og' );
		if( $thumbnail_attachment && $thumbnail_attachment[1] >= 1200 && $thumbnail_attachment[2] >= 630 ) {
			$thumbnail_url = get_the_post_thumbnail_url( $post, 'article-og' );
			$thumbnail_width = $thumbnail_attachment[1];
			$thumbnail_height = $thumbnail_attachment[2];
		}
	} else if( $page_slug != 'home' ) {
		$site_title .= wp_title( '—', false );
		$og_title = wp_title( '—', false, 'right' ) . $og_title;
	}
	?>
	<title><?= $site_title ?></title>
	<meta property="description" content="<?= $description ?>" />
	<meta charset="<?= get_bloginfo( 'charset' ) ?>" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<meta property="og:site_name" content="<?= $site_title ?>" />
	<meta property="og:url" content="<?= $url ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="<?= $og_title ?>" />
	<meta property="og:description" content="<?= $description ?>" />
	<meta property="og:image" content="<?= $thumbnail_url ?>" />
	<meta property="og:image:width" content="<?= $thumbnail_width ?>" />
	<meta property="og:image:height" content="<?= $thumbnail_height ?>" />

	<meta name="twitter:card" content="summary_large_image" />
	<meta property="twitter:domain" content="therevealer.org" />
	<meta property="twitter:url" content="<?= $url ?>" />
	<meta name="twitter:title" content="<?= $og_title ?>" />
	<meta name="twitter:description" content="<?= $description ?>" />
	<meta name="twitter:image" content="<?= $thumbnail_url ?>" />
	
	<?php wp_head(); ?>
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