<?php
get_header();
echo '<div class="contributor readable">';
	echo '<div class="max">';
		$posts_args = array(
			'posts_per_page' => 10,
			'tag' => $post->post_name
		);
		echo '<div class="loop posts medium masonry">';
			query_posts( $posts_args );
			if ( have_posts() ) {
				while ( have_posts() ) {
					the_post();
					get_template_part( 'snippets/article' );
				}
			}
		echo '</div>';
	echo '</div>';
echo '</div>';

get_footer();
?>