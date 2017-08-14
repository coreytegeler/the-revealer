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
echo '<header>';
	echo '<div class="max">';
		echo '<div class="wrap">';
			$logoSvg = get_template_directory_uri() . '/assets/images/logo.svg';
			$home_url = get_site_url();
			echo '<a href="' . $home_url . '">';
				echo file_get_contents( $logoSvg );
			echo '</a>';
			get_template_part( 'snippets/nav' );
		echo '</div>';
	echo '</div>';
echo '</header>';
echo '<div class="bar top"><div class="solid"></div></div>';
?>