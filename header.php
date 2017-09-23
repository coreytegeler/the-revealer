<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
<?php
global $post;
$page_type = $post->post_type;
$page_slug = $post->post_name;
// echo $page_type;
$full_pages = array( 'home', 'about', 'search', 'discover', 'events' );
if( is_home() ) {
	$page_slug = 'home';
	$body_style = 'full';
} else if( in_array( $page_slug, $full_pages ) || is_search() ) {
	$body_style = 'full';
} else {
	$page_slug = $post->post_name;
	$body_style = 'split';
}
if( is_search() || $page_slug == 'search' ) {
	$page_slug = 'search';
}
?>
	<title>
		<?php
		bloginfo('name');
		if( !is_home() ) {
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
<body <?php body_class( array( $page_slug, $page_type, $body_style ) ) ?> data-site-url="<?php echo get_site_url() ?>">
<?php
// if( $page_type == 'post' ) {
	// echo '<div id="top_bar">';
		// if( is_archived() ) {
		// 	echo '<div id="alert"><div class="inner">This is an archived post, missing media and broken links are expected. Please help by reporting any issues to <a href="#">admin@therevealer.org</a></div></div>';
		// }
	// echo '</div>';
// }
get_template_part( 'parts/header' );
echo '<div id="wrapper">';
	wp_reset_query();
	if( !in_array( $page_slug, $full_pages ) && !is_search() ) {
		get_template_part( 'parts/side' );
	}
	echo '<main>';
?>