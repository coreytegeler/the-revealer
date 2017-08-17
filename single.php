<?php
global $post;
get_header();
echo '<div class="single">';
	echo '<article class="post readable">';
		$content = $post->post_content;
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );
		$stripped_content = strip_tags( $content );
		
		echo '<div class="text">';
			echo '<div class="content">';
				echo '<div class="max">';
					echo $content;
				echo '</div>';
			echo '</div>';
		echo '</div>';
	echo '</article>';
echo '</div>';
get_footer();
?>