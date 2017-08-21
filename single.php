<?php
global $post;
get_header();
echo '<div class="single">';
	if( is_archived() ) {
		// echo '<div class="alert">This is an archived post, missing media and broken links are expected. Please help by reporting any issues to <a href="#">admin@therevealer.org</a></div>';
	}
	echo '<article class="post readable">';
		$content = $post->post_content;
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		$stripped_content = strip_tags( $content );
		echo '<div class="max">';
			echo '<div class="text">';
				echo '<div class="content">';
					echo $content;
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</article>';
echo '</div>';
get_footer();
?>