<?php
get_header();
echo '<div class="posts contributors">';
	echo '<div class="max">';
		$posts_args = array(
			'post_type' => 'contributor',
			'posts_per_page' => -1
		);
		query_posts( $posts_args );
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				$title = $post->post_title;
				$thumb = get_the_post_thumbnail_url( $post );
				$slug = $post->post_name;
				$link = '/tag/' . $slug;
				$excerpt = strip_tags( get_the_content() );
				echo '<article class="mini contributor">';	
					echo '<div class="text">';
						// echo '<div class="title">';
						// 	echo '<h2>' . $title . '</h2>';
						// echo '</div>';
						echo '<div class="excerpt">';
							echo '<a href="/" class="title">' . $title . '</a> ' . $excerpt;
						echo '</div>';
						echo '<a href="' . $url . '" class="more conceal">Read their articles</a>';
					echo '</div>';
				echo '</article>';
			}
		}
	echo '</div>';
echo '</div>';
get_footer();
?>