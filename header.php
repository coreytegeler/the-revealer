<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<title>
		<?php
		bloginfo('name');
		$page_slug = $post->post_type;
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
<body <?php echo body_class( $page_slug ) ?>>
<?php
echo '<div id="wrapper" class="fixed">';
	echo '<aside>';
		echo '<div id="logo">';
			$logoSvg = get_template_directory_uri() . '/assets/images/logo.svg';
			$home_url = get_site_url();
			echo '<a href="' . $home_url . '">';
				echo file_get_contents( $logoSvg );
			echo '</a>';
		echo '</div>';
		if( is_single() ) {
			get_template_part( 'snippets/single-header' );
		}

	echo '</aside>';
	echo '<main>';
		get_template_part( 'snippets/nav' );
		// echo '<h1>a review of religion & media</h1>';
	// echo '<div class="bar top"><div class="solid"></div></div>';
?>