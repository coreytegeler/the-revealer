<?php
global $post;
echo '<aside>';
	echo '<div id="logo">';
		$logo_svg = get_template_directory_uri() . '/assets/images/logo.svg';
		$home_url = get_site_url();
		echo '<a class="svg" href="' . $home_url . '">';
			echo file_get_contents( $logo_svg );
		echo '</a>';
	echo '</div>';
	$post_type = $post->post_type;
	if( is_home() || is_archive() ) {
		$post_type = 'home';
	}
	$post_types = array( 'home', 'posts', 'post', 'contributor' );
	if( in_array( $post_type, $post_types ) ) {
		echo '<div class="content">';
			get_template_part( 'header', $post_type );
		echo '</div>';
	}
echo '</aside>';
?>