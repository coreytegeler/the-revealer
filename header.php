<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
<?php
global $post;
$page_type = $post->post_type;
$page_slug = $post->post_name;
$split_pages = array( 'article', 'articles' );
$wrapper_style = 'full';
if( is_home() ) {
	$page_slug = 'home';
} else if( is_search() || $page_slug == 'search' ) {
	$page_slug = 'search';
} else if( in_array( $page_slug, $split_pages ) ) {
	$wrapper_style = 'split';
} else if( is_404() ) {
	$page_slug = 'article';
} else if( $page_type == 'post' && !is_tag() ) {
	$page_slug = 'article';
	$wrapper_style = 'split';
} else {
	$page_slug = $post->post_name;;
}
?>
	<title>
		<?php
		bloginfo( 'name' );
		if( $page_slug != 'home' ) {
			echo ' — ';
			wp_title('', true);
		}
		?>
	</title>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
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