<?php
global $post;
echo '<aside>';
	

	// get_template_part( 'parts/mobile-nav' );

	$page_type = $post->post_type;
	$page_slug = $post->post_name;
	if( is_home() || is_archive() || $page_slug == 'posts' ) {
		$page_type = 'home';
	}

	$page_types = array( 'home', 'posts', 'post', 'contributor' );
	if( in_array( $page_type, $page_types ) ) {
		echo '<div class="content">';
			get_template_part( 'side', $page_type );
		echo '</div>';
	}
echo '</aside>';
?>