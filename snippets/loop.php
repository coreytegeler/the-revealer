<?php
echo '<div class="loop posts">';
	if ( have_posts() ) {
		$i = 0;
		while ( have_posts() ) {
			the_post();
			$title = $post->post_title;
			$thumb = get_the_post_thumbnail_url( $post );
			$permalink = get_the_permalink();
			$excerpt = wp_strip_all_tags( get_the_excerpt() );
			if( $i < 3 ){
				$size = 'large';
			} else {
				$size = 'small';
			}
			echo '<article class="' . $size . '">';
				echo '<a href="' . $permalink . '" class="image conceal">';
					if ( $thumb ) {
						echo '<img src="' . $thumb . '"/>';
					} else {
						echo '<div class="missing">';
							$questionSvg = get_template_directory_uri() . '/assets/images/question.svg';
							echo file_get_contents( $questionSvg );
						echo '</div>';
					}
				echo '</a>';
				echo '<div class="info">';
					echo '<div class="title">';
						echo '<a href="' . $permalink . '">';
							echo '<h1>' . $title . '</h1>';
						echo '</a>';
					echo '</div>';
					echo '<div class="meta">';
						echo '<span>Published on ' . $date . '</span>';
					echo '</div>';
					echo '<div class="excerpt">';
						echo $excerpt;
					echo '</div>';
				echo '</div>';
			echo '</article>';
			$i++;
		}
	}
echo '</div>';
?>