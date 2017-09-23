<?php
global $post;
echo '<aside>';
	$page_type = $post->post_type;
	$page_slug = $post->post_name;
	if( is_home() ) {
		$page_type = 'home';
	} else if( $page_slug == 'articles' ) {
		$page_type = 'articles';
	}
	$page_types = array( 'home', 'articles', 'post', 'contributor' );

	if( in_array( $page_type, $page_types ) ) {
		echo '<div class="content">';
			get_template_part( 'side', $page_type );
		echo '</div>';
	}
echo '</aside>';
?>