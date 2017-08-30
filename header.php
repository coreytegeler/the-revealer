<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<title>
		<?php
		bloginfo('name');
		$page_type = $post->post_type;
		$page_slug = $post->post_name;
		if( !is_home() ) {
			echo ' — ';
			wp_title('',true);
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
if( is_archived() ) {
	echo '<div id="alert"><div class="inner">This is an archived post, missing media and broken links are expected. Please help by reporting any issues to <a href="#">admin@therevealer.org</a></div></div>';
}
// echo '<div id="gradient"></div>';
echo '<div id="wrapper" class="fixed">';
	get_template_part( 'parts/side' );
	echo '<main>';
		get_template_part( 'parts/nav' );
		// echo '<h1>a review of religion & media</h1>';
	// echo '<div class="bar top"><div class="solid"></div></div>';
?>